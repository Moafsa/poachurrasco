<?php

namespace App\Http\Controllers;

use App\Models\BbqGuide;
use App\Models\BbqChat;
use App\Services\BbqAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BbqPortalController extends Controller
{
    private BbqAiService $aiService;

    public function __construct(BbqAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Show the BBQ portal homepage
     */
    public function index()
    {
        $featuredGuides = BbqGuide::featured()->latest()->take(6)->get();
        $recentGuides = BbqGuide::latest()->take(8)->get();
        
        $meatTypes = BbqGuide::distinct()->pluck('meat_type')->filter();
        $difficultyLevels = ['easy' => 'Fácil', 'medium' => 'Médio', 'hard' => 'Difícil'];

        return view('bbq-portal.index', compact(
            'featuredGuides',
            'recentGuides',
            'meatTypes',
            'difficultyLevels'
        ));
    }

    /**
     * Show all BBQ guides with filters
     */
    public function guides(Request $request)
    {
        $query = BbqGuide::query();

        // Apply filters
        if ($request->filled('meat_type')) {
            $query->byMeatType($request->meat_type);
        }

        if ($request->filled('difficulty')) {
            $query->byDifficulty($request->difficulty);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('meat_type', 'like', "%{$search}%")
                  ->orWhere('cut_type', 'like', "%{$search}%");
            });
        }

        $guides = $query->latest()->paginate(12);
        
        $meatTypes = BbqGuide::distinct()->pluck('meat_type')->filter();
        $difficultyLevels = ['easy' => 'Fácil', 'medium' => 'Médio', 'hard' => 'Difícil'];

        return view('bbq-portal.guides', compact(
            'guides',
            'meatTypes',
            'difficultyLevels'
        ));
    }

    /**
     * Show a specific BBQ guide
     */
    public function showGuide(BbqGuide $guide)
    {
        $relatedGuides = BbqGuide::where('meat_type', $guide->meat_type)
            ->where('id', '!=', $guide->id)
            ->take(4)
            ->get();

        return view('bbq-portal.guide-detail', compact('guide', 'relatedGuides'));
    }

    /**
     * Show the AI chat interface
     */
    public function chat()
    {
        $sessionId = session('bbq_chat_session', Str::uuid());
        session(['bbq_chat_session' => $sessionId]);

        $recentMessages = BbqChat::bySession($sessionId)
            ->latest()
            ->take(10)
            ->get()
            ->reverse();

        $context = $this->buildChatContext();

        return view('bbq-portal.chat', compact('recentMessages', 'context'));
    }

    /**
     * Handle AI chat messages
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
        ]);

        try {
            $sessionId = session('bbq_chat_session', Str::uuid());
            session(['bbq_chat_session' => $sessionId]);

            // Handle image analysis
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageData = base64_encode(file_get_contents($image->getPathname()));
                
                // Create enhanced prompt for image analysis
                $prompt = $request->message ?: 'Analise esta foto do meu churrasco e me diga sobre calorias, dicas e tudo mais!';
                $enhancedPrompt = $prompt . "\n\nPor favor, analise a imagem e forneça:\n- Estimativa de calorias por porção\n- Identificação dos alimentos\n- Dicas de preparo\n- Sugestões de melhorias\n- Informações nutricionais";
                
                $aiResponse = $this->aiService->analyzeImage($imageData, $enhancedPrompt);
                $responseText = $aiResponse['text'];
                $audioUrl = $aiResponse['audio_url'];
            } else {
                // Regular text message
                $context = $this->buildChatContext();
                $recentMessages = BbqChat::bySession($sessionId)
                    ->latest()
                    ->take(5)
                    ->get()
                    ->reverse()
                    ->pluck('message')
                    ->toArray();

                $context['recent_messages'] = $recentMessages;
                $aiResponse = $this->aiService->generateResponse($request->message, $context);
                $responseText = $aiResponse['text'];
                $audioUrl = $aiResponse['audio_url'];
            }

            // Save chat to database
            BbqChat::create([
                'user_id' => Auth::id() ?? 1, // Fallback for testing
                'session_id' => $sessionId,
                'message' => $request->message ?: 'Análise de imagem',
                'response' => $responseText,
                'context' => json_encode($context ?? []),
                'is_ai_message' => false,
            ]);

            return response()->json([
                'success' => true,
                'ai_response' => [
                    'response' => $responseText,
                    'audio_url' => $audioUrl,
                    'context' => $context ?? []
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate a new BBQ guide using AI
     */
    public function generateGuide(Request $request)
    {
        $request->validate([
            'meat_type' => 'required|string',
            'cut_type' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
        ]);

        $parameters = $request->only(['meat_type', 'cut_type', 'difficulty']);
        $guideData = $this->aiService->generateBbqGuide($parameters);

        // Create the guide in database
        $guide = BbqGuide::create($guideData);

        return response()->json([
            'success' => true,
            'guide' => $guide,
            'redirect_url' => route('bbq-portal.guide.show', $guide),
        ]);
    }

    /**
     * Get chat history for a session
     */
    public function getChatHistory(Request $request)
    {
        $sessionId = $request->session_id ?? session('bbq_chat_session');
        
        if (!$sessionId) {
            return response()->json(['messages' => []]);
        }

        $messages = BbqChat::bySession($sessionId)
            ->latest()
            ->take(20)
            ->get()
            ->reverse();

        return response()->json(['messages' => $messages]);
    }

    /**
     * Build context for AI chat
     */
    private function buildChatContext(): array
    {
        $guides = BbqGuide::latest()->take(5)->get()->map(function($guide) {
            return [
                'title' => $guide->title,
                'description' => $guide->description,
                'meat_type' => $guide->meat_type,
                'cut_type' => $guide->cut_type,
                'difficulty_level' => $guide->difficulty_level,
            ];
        })->toArray();

        return [
            'guides' => $guides,
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Show BBQ calculator page
     */
    public function calculator()
    {
        return view('bbq-portal.calculator');
    }

    /**
     * Calculate BBQ portions and ingredients
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'people' => 'required|integer|min:1|max:100',
            'meat_type' => 'required|string',
            'appetite_level' => 'required|in:light,medium,heavy',
        ]);

        $people = $request->people;
        $meatType = $request->meat_type;
        $appetiteLevel = $request->appetite_level;

        // Calculate portions based on appetite level
        $gramsPerPerson = match($appetiteLevel) {
            'light' => 200,
            'medium' => 300,
            'heavy' => 400,
            default => 300
        };

        $totalGrams = $people * $gramsPerPerson;
        $totalKg = round($totalGrams / 1000, 2);

        // Calculate accompaniments
        $accompaniments = $this->calculateAccompaniments($people);

        // Calculate cooking time
        $cookingTime = $this->calculateCookingTime($meatType, $totalKg);

        return response()->json([
            'success' => true,
            'people' => $people,
            'meat_type' => $meatType,
            'appetite_level' => $appetiteLevel,
            'total_grams' => $totalGrams,
            'total_kg' => $totalKg,
            'grams_per_person' => $gramsPerPerson,
            'accompaniments' => $accompaniments,
            'cooking_time' => $cookingTime,
        ]);
    }

    /**
     * Calculate accompaniments for BBQ
     */
    private function calculateAccompaniments(int $people): array
    {
        return [
            'farofa' => [
                'amount' => round($people * 0.1, 1),
                'unit' => 'kg',
                'description' => 'Farofa de mandioca'
            ],
            'vinagrete' => [
                'amount' => round($people * 0.15, 1),
                'unit' => 'kg',
                'description' => 'Vinagrete de tomate e cebola'
            ],
            'pão_de_alho' => [
                'amount' => $people * 2,
                'unit' => 'unidades',
                'description' => 'Pão de alho'
            ],
            'salada_verde' => [
                'amount' => round($people * 0.2, 1),
                'unit' => 'kg',
                'description' => 'Salada verde mista'
            ],
            'bebidas' => [
                'amount' => $people * 2,
                'unit' => 'litros',
                'description' => 'Refrigerantes e água'
            ],
        ];
    }

    /**
     * Calculate cooking time based on meat type and quantity
     */
    private function calculateCookingTime(string $meatType, float $totalKg): array
    {
        $baseTime = match($meatType) {
            'bovina' => 45,
            'suina' => 60,
            'frango' => 30,
            'cordeiro' => 50,
            default => 45
        };

        // Adjust time based on quantity
        $timeMultiplier = min(1.5, 1 + ($totalKg - 1) * 0.1);
        $totalTime = round($baseTime * $timeMultiplier);

        return [
            'preparation_time' => 30,
            'cooking_time' => $totalTime,
            'resting_time' => 15,
            'total_time' => 30 + $totalTime + 15,
            'description' => "Tempo estimado para {$totalKg}kg de {$meatType}"
        ];
    }
}
