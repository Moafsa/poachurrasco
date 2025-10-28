<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BbqAiService
{
    private string $apiKey;
    private string $model;
    private int $maxTokens;
    private ElevenLabsService $elevenLabsService;

    public function __construct(ElevenLabsService $elevenLabsService)
    {
        $this->apiKey = config('services.openai.api_key');
        $this->model = config('services.openai.model');
        $this->maxTokens = config('services.openai.max_tokens');
        $this->elevenLabsService = $elevenLabsService;
    }

    /**
     * Analyze image with AI
     */
    public function analyzeImage(string $imageData, string $prompt): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4-vision-preview',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $this->getGauchoSystemPrompt()
                    ],
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => $prompt
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => 'data:image/jpeg;base64,' . $imageData
                                ]
                            ]
                        ]
                    ]
                ],
                'max_tokens' => $this->maxTokens,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $textResponse = $data['choices'][0]['message']['content'] ?? 'Desculpa, tchê! Não consegui analisar a imagem. Tenta de novo?';
                
                // Generate audio for the response
                $audioUrl = $this->elevenLabsService->generateAudio($textResponse);
                
                return [
                    'text' => $textResponse,
                    'audio_url' => $audioUrl
                ];
            }

            Log::error('OpenAI Vision API error', ['response' => $response->body()]);
            $errorResponse = 'Bah, deu um probleminha para analisar a imagem. Tenta de novo daqui a pouco!';
            return [
                'text' => $errorResponse,
                'audio_url' => null
            ];

        } catch (\Exception $e) {
            Log::error('OpenAI Vision Service error', ['error' => $e->getMessage()]);
            $errorResponse = 'Eita, deu um erro para analisar a imagem. Mas não desiste não, tenta de novo!';
            return [
                'text' => $errorResponse,
                'audio_url' => null
            ];
        }
    }

    /**
     * Generate AI response with gaúcho personality
     */
    public function generateResponse(string $userMessage, array $context = []): array
    {
        try {
            $systemPrompt = $this->getGauchoSystemPrompt();
            $contextPrompt = $this->buildContextPrompt($context);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt . "\n\n" . $contextPrompt
                    ],
                    [
                        'role' => 'user',
                        'content' => $userMessage
                    ]
                ],
                'max_tokens' => $this->maxTokens,
                'temperature' => 0.8,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $textResponse = $data['choices'][0]['message']['content'] ?? 'Desculpa, não consegui responder agora. Tenta de novo!';
                
                // Generate audio for the response
                $audioUrl = $this->elevenLabsService->generateAudio($textResponse);
                
                return [
                    'text' => $textResponse,
                    'audio_url' => $audioUrl
                ];
            }

            Log::error('OpenAI API error', ['response' => $response->body()]);
            $errorResponse = 'Bah, deu um probleminha aqui. Tenta de novo daqui a pouco!';
            return [
                'text' => $errorResponse,
                'audio_url' => null
            ];

        } catch (\Exception $e) {
            Log::error('OpenAI Service error', ['error' => $e->getMessage()]);
            $errorResponse = 'Eita, deu um erro aqui. Mas não desiste não, tenta de novo!';
            return [
                'text' => $errorResponse,
                'audio_url' => null
            ];
        }
    }

    /**
     * Get the gaúcho system prompt
     */
    private function getGauchoSystemPrompt(): string
    {
        return "Você é um especialista em churrasco gaúcho com personalidade autêntica do Rio Grande do Sul. 
        
        PERSONALIDADE:
        - Fale com sotaque gaúcho leve e natural
        - Use expressões típicas como 'bah', 'tchê', 'tri', 'pessoa', 'gente'
        - Seja caloroso, hospitaleiro e apaixonado pelo churrasco
        - Tenha conhecimento profundo sobre carnes, cortes, temperos e técnicas
        - Seja didático mas descontraído
        
        CONHECIMENTO TÉCNICO:
        - Domine todos os tipos de carne: bovina, suína, frango, cordeiro
        - Conheça todos os cortes: picanha, costela, fraldinha, maminha, etc.
        - Saiba sobre temperos: sal grosso, chimichurri, ervas, marinadas
        - Entenda técnicas: ponto da carne, temperatura, tempo de cocção
        - Conheça equipamentos: churrasqueira, espetos, facas, termômetros
        - Saiba sobre acompanhamentos: farofa, vinagrete, pão de alho
        - Calcule calorias e valores nutricionais
        
        ESTILO DE RESPOSTA:
        - Seja conversacional e amigável
        - Use analogias e comparações do dia a dia
        - Dê dicas práticas e segredos do churrasco
        - Explique o 'porquê' das coisas, não só o 'como'
        - Se não souber algo, admita e sugira onde buscar informação
        - SEMPRE responda em sentenças curtas e naturais, como se estivesse conversando
        - Use parágrafos curtos para facilitar a leitura
        - Quebre informações complexas em partes menores
        - IMPORTANTE: Use quebras de linha (\n) entre cada sentença para facilitar a leitura
        - Cada sentença deve estar em uma linha separada
        - NUNCA assuma o gênero da pessoa - use termos neutros como 'pessoa', 'gente', 'tchê'
        - Evite usar 'guria', 'guri', 'moça', 'moço' a menos que a pessoa especifique seu gênero
        
        IMPORTANTE: 
        - Sempre responda em português brasileiro com sotaque gaúcho natural
        - Use sentenças curtas e diretas
        - Simule uma conversa humana real
        - Seja específico com números e medidas quando possível
        - Trate todos com respeito e sem suposições de gênero";
    }

    /**
     * Build context prompt based on available guides
     */
    private function buildContextPrompt(array $context): string
    {
        if (empty($context)) {
            return "Você está conversando sobre churrasco gaúcho. Use todo seu conhecimento para ajudar o usuário.";
        }

        $contextText = "CONTEXTO DA CONVERSA:\n";
        
        if (isset($context['guides']) && !empty($context['guides'])) {
            $contextText .= "Guias de churrasco disponíveis:\n";
            foreach ($context['guides'] as $guide) {
                $contextText .= "- {$guide['title']}: {$guide['description']}\n";
                $contextText .= "  Tipo de carne: {$guide['meat_type']}\n";
                $contextText .= "  Corte: {$guide['cut_type']}\n";
                $contextText .= "  Dificuldade: {$guide['difficulty_level']}\n\n";
            }
        }

        if (isset($context['recent_messages']) && !empty($context['recent_messages'])) {
            $contextText .= "Mensagens recentes da conversa:\n";
            foreach ($context['recent_messages'] as $message) {
                $contextText .= "- {$message}\n";
            }
        }

        return $contextText;
    }

    /**
     * Generate BBQ guide content using AI
     */
    public function generateBbqGuide(array $parameters): array
    {
        try {
            $prompt = $this->buildGuidePrompt($parameters);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $this->getGauchoSystemPrompt()
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 2000,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? '';
                return $this->parseGuideContent($content, $parameters);
            }

            return $this->getDefaultGuide($parameters);

        } catch (\Exception $e) {
            Log::error('OpenAI Guide generation error', ['error' => $e->getMessage()]);
            return $this->getDefaultGuide($parameters);
        }
    }

    /**
     * Build prompt for guide generation
     */
    private function buildGuidePrompt(array $parameters): string
    {
        $meatType = $parameters['meat_type'] ?? 'bovina';
        $cutType = $parameters['cut_type'] ?? 'picanha';
        $difficulty = $parameters['difficulty'] ?? 'medium';

        return "Crie um guia completo de churrasco para {$meatType} - corte {$cutType} com dificuldade {$difficulty}.
        
        O guia deve incluir:
        1. Título atrativo
        2. Descrição detalhada
        3. Lista de ingredientes e temperos
        4. Passos detalhados de preparo
        5. Tempo de cocção e temperaturas
        6. Equipamentos necessários
        7. Dicas especiais
        8. Sugestões de acompanhamentos
        9. Informações nutricionais (calorias por 100g)
        10. Número de porções
        
        Responda em formato JSON estruturado com os campos: title, description, ingredients, steps, cooking_time, temperature_guide, equipment_needed, tips, serving_suggestions, calories_per_100g, servings.";
    }

    /**
     * Parse AI generated content into structured data
     */
    private function parseGuideContent(string $content, array $parameters): array
    {
        // Try to extract JSON from the response
        $jsonStart = strpos($content, '{');
        $jsonEnd = strrpos($content, '}');
        
        if ($jsonStart !== false && $jsonEnd !== false) {
            $jsonContent = substr($content, $jsonStart, $jsonEnd - $jsonStart + 1);
            $decoded = json_decode($jsonContent, true);
            
            if ($decoded) {
                return array_merge($parameters, $decoded);
            }
        }

        // Fallback to default guide if parsing fails
        return $this->getDefaultGuide($parameters);
    }

    /**
     * Get default guide when AI fails
     */
    private function getDefaultGuide(array $parameters): array
    {
        $meatType = $parameters['meat_type'] ?? 'bovina';
        $cutType = $parameters['cut_type'] ?? 'picanha';

        return array_merge($parameters, [
            'title' => "Churrasco de {$cutType} - Tradição Gaúcha",
            'description' => "Um guia completo para fazer o melhor churrasco de {$cutType} seguindo a tradição gaúcha.",
            'ingredients' => ['Sal grosso', 'Carne de qualidade', 'Temperos a gosto'],
            'steps' => [
                'Tempere a carne com sal grosso',
                'Prepare a churrasqueira',
                'Grelhe a carne no ponto desejado'
            ],
            'cooking_time' => '30-45 minutos',
            'temperature_guide' => 'Fogo médio',
            'equipment_needed' => 'Churrasqueira, espetos, faca',
            'tips' => 'Use sempre carne de qualidade e não tenha pressa',
            'serving_suggestions' => 'Farofa, vinagrete, pão de alho',
            'calories_per_100g' => 250,
            'servings' => 4,
        ]);
    }
}
