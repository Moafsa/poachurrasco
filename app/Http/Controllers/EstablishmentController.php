<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Establishment;
use App\Services\EstablishmentApiService;
use App\Jobs\ProcessEstablishmentJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class EstablishmentController extends Controller
{
    public function index()
    {
        $establishments = Establishment::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('dashboard.establishments.index', compact('establishments'));
    }

    public function create()
    {
        return view('dashboard.establishments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2',
            'zip_code' => 'required|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'category' => 'required|in:churrascaria,açougue,supermercado,restaurante,bar,lanchonete,delivery,outros',
            'opening_hours' => 'nullable|array',
            'payment_methods' => 'nullable|array',
            'amenities' => 'nullable|array',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        // Handle file uploads
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('establishments/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('establishments/covers', 'public');
        }

        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('establishments/images', 'public');
            }
            $validated['images'] = $imagePaths;
        }

        $establishment = Establishment::create($validated);

        // Queue job to search and merge external data
        ProcessEstablishmentJob::dispatch($establishment->id, 'merge')
            ->delay(now()->addSeconds(30));

        return redirect()->route('establishments.show', $establishment)
            ->with('success', 'Estabelecimento cadastrado com sucesso! Os dados serão sincronizados automaticamente.');
    }

    public function show(Establishment $establishment)
    {
        $this->authorize('view', $establishment);
        
        return view('dashboard.establishments.show', compact('establishment'));
    }

    public function edit(Establishment $establishment)
    {
        $this->authorize('update', $establishment);
        
        return view('dashboard.establishments.edit', compact('establishment'));
    }

    public function update(Request $request, Establishment $establishment)
    {
        $this->authorize('update', $establishment);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2',
            'zip_code' => 'required|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'category' => 'required|in:churrascaria,açougue,supermercado,restaurante,bar,lanchonete,delivery,outros',
            'opening_hours' => 'nullable|array',
            'payment_methods' => 'nullable|array',
            'amenities' => 'nullable|array',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file uploads
        if ($request->hasFile('logo')) {
            if ($establishment->logo) {
                Storage::disk('public')->delete($establishment->logo);
            }
            $validated['logo'] = $request->file('logo')->store('establishments/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            if ($establishment->cover_image) {
                Storage::disk('public')->delete($establishment->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('establishments/covers', 'public');
        }

        if ($request->hasFile('images')) {
            // Delete old images
            if ($establishment->images) {
                foreach ($establishment->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('establishments/images', 'public');
            }
            $validated['images'] = $imagePaths;
        }

        $establishment->update($validated);

        return redirect()->route('establishments.show', $establishment)
            ->with('success', 'Estabelecimento atualizado com sucesso!');
    }

    public function destroy(Establishment $establishment)
    {
        $this->authorize('delete', $establishment);

        // Delete associated files
        if ($establishment->logo) {
            Storage::disk('public')->delete($establishment->logo);
        }
        if ($establishment->cover_image) {
            Storage::disk('public')->delete($establishment->cover_image);
        }
        if ($establishment->images) {
            foreach ($establishment->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $establishment->delete();

        return redirect()->route('establishments.index')
            ->with('success', 'Estabelecimento excluído com sucesso!');
    }

    // Public API methods
    public function publicIndex(Request $request)
    {
        $query = Establishment::active()->verified();

        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('latitude') && $request->has('longitude')) {
            $query->nearby($request->latitude, $request->longitude, $request->get('radius', 10));
        }

        $establishments = $query->paginate(20);

        return response()->json($establishments);
    }

    public function publicShow(Establishment $establishment)
    {
        if ($establishment->status !== 'active') {
            return response()->json(['error' => 'Estabelecimento não encontrado'], 404);
        }

        return response()->json($establishment->load(['products', 'services', 'recipes']));
    }

    /**
     * Proxy para servir imagens da Google Places API
     */
    public function proxyPhoto($id, $index = 0)
    {
        $establishment = Establishment::findOrFail($id);
        
        if (!$establishment->photo_urls || count($establishment->photo_urls) === 0) {
            Log::warning('No photo URLs for establishment', ['id' => $id]);
            return $this->getPlaceholderImage();
        }
        
        $index = (int) $index;
        if ($index < 0 || $index >= count($establishment->photo_urls)) {
            $index = 0;
        }
        
        try {
            $photoUrl = $establishment->photo_urls[$index];
            
            if (empty($photoUrl)) {
                Log::warning('Empty photo URL', ['id' => $id, 'index' => $index]);
                return $this->getPlaceholderImage();
            }
            
            Log::info('Fetching photo', [
                'id' => $id,
                'index' => $index,
                'url' => substr($photoUrl, 0, 100) . '...'
            ]);
            
            // Use HTTP client with proper user agent and headers
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'image/webp,image/apng,image/*,*/*;q=0.8',
                    'Referer' => url('/'),
                ])
                ->get($photoUrl);
            
            if ($response->successful()) {
                $contentType = $response->header('Content-Type') ?? 'image/jpeg';
                $imageData = $response->body();
                
                // Check if response is actually an image (not HTML error page)
                if (empty($imageData) || strpos($imageData, '<!DOCTYPE') !== false || strpos($imageData, '<html') !== false) {
                    Log::warning('Invalid image data received (HTML instead of image)', [
                        'id' => $id, 
                        'index' => $index,
                        'content_type' => $contentType,
                        'first_bytes' => substr($imageData, 0, 100)
                    ]);
                    return $this->getPlaceholderImage();
                }
                
                // Verify it's actually image data
                $imageInfo = @getimagesizefromstring($imageData);
                if ($imageInfo === false) {
                    Log::warning('Invalid image format received', ['id' => $id, 'index' => $index]);
                    return $this->getPlaceholderImage();
                }
                
                Log::info('Photo fetched successfully', [
                    'id' => $id,
                    'index' => $index,
                    'size' => strlen($imageData),
                    'content_type' => $contentType,
                    'image_width' => $imageInfo[0] ?? null,
                    'image_height' => $imageInfo[1] ?? null
                ]);
                
                return response($imageData, 200)
                    ->header('Content-Type', $contentType)
                    ->header('Cache-Control', 'public, max-age=3600')
                    ->header('Content-Length', strlen($imageData))
                    ->header('X-Content-Type-Options', 'nosniff');
            }
            
            Log::warning('Failed to fetch photo', [
                'id' => $id,
                'index' => $index,
                'status' => $response->status()
            ]);
            
            return $this->getPlaceholderImage();
        } catch (\Exception $e) {
            Log::error('Error proxying photo: ' . $e->getMessage(), [
                'establishment_id' => $id,
                'photo_index' => $index,
                'photo_url' => $establishment->photo_urls[$index] ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            return $this->getPlaceholderImage();
        }
    }
    
    /**
     * Get placeholder image response
     */
    private function getPlaceholderImage()
    {
        $placeholderPath = public_path('images/placeholder.jpg');
        if (file_exists($placeholderPath)) {
            return response()->file($placeholderPath);
        }
        
        // Return a simple SVG placeholder if GD is not available
        $svg = '<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
            <rect width="400" height="300" fill="#f97316"/>
            <text x="50%" y="50%" font-family="Arial" font-size="24" fill="white" text-anchor="middle" dominant-baseline="middle">Sem Imagem</text>
        </svg>';
        
        return response($svg, 200)->header('Content-Type', 'image/svg+xml');
    }

    /**
     * Sync establishment with external API
     */
    public function sync(Establishment $establishment)
    {
        $this->authorize('update', $establishment);
        
        try {
            ProcessEstablishmentJob::dispatch($establishment->id, 'sync');
            
            return redirect()->back()
                ->with('success', 'Sincronização iniciada! Os dados serão atualizados em breve.');
        } catch (\Exception $e) {
            Log::error('Error dispatching sync job: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Erro ao iniciar sincronização. Tente novamente.');
        }
    }

    /**
     * Search for external establishments
     */
    public function searchExternal(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:255',
            'radius' => 'nullable|integer|min:1000|max:50000',
        ]);

        try {
            $apiService = new EstablishmentApiService();
            $establishments = $apiService->searchEstablishmentsInPortoAlegre(
                $request->query,
                $request->get('radius', 50000)
            );

            return response()->json([
                'success' => true,
                'establishments' => $establishments,
                'count' => count($establishments)
            ]);
        } catch (\Exception $e) {
            Log::error('Error searching external establishments: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erro ao buscar estabelecimentos externos'
            ], 500);
        }
    }

    /**
     * Import external establishment
     */
    public function importExternal(Request $request)
    {
        $request->validate([
            'external_id' => 'required|string',
            'external_source' => 'required|string',
        ]);

        try {
            $apiService = new EstablishmentApiService();
            
            // Check if already exists
            $existing = Establishment::where('external_id', $request->external_id)
                ->where('external_source', $request->external_source)
                ->first();
                
            if ($existing) {
                return response()->json([
                    'success' => false,
                    'error' => 'Estabelecimento já existe no sistema'
                ], 400);
            }

            // Get place details
            $details = $apiService->getPlaceDetails($request->external_id);
            
            if (!$details) {
                return response()->json([
                    'success' => false,
                    'error' => 'Estabelecimento não encontrado na API externa'
                ], 404);
            }

            // Process and create establishment
            $establishmentData = $apiService->processGooglePlaceData($details);
            $establishment = Establishment::create($establishmentData);

            return response()->json([
                'success' => true,
                'establishment' => $establishment,
                'message' => 'Estabelecimento importado com sucesso'
            ]);
        } catch (\Exception $e) {
            Log::error('Error importing external establishment: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Erro ao importar estabelecimento'
            ], 500);
        }
    }

    /**
     * Get establishments map data
     */
    public function mapData(Request $request)
    {
        $isDashboardContext = $request->routeIs('establishments.map.data');

        if ($isDashboardContext && auth()->check()) {
            // Dashboard context: only user's active establishments
            $query = Establishment::active()->where('user_id', auth()->id());
        } else {
            // Public context: include external establishments based on system settings
            try {
                $showExternal = \App\Models\SystemSetting::showExternalEstablishments();
            } catch (\Exception $e) {
                // Fallback if SystemSetting model is not available
                $showExternal = true;
            }
            $includeExternal = $request->boolean('include_external', $showExternal);
            
            $query = Establishment::where(function ($q) use ($includeExternal) {
                // Include active user-created establishments
                $q->where(function ($subQ) {
                    $subQ->where('status', 'active')
                         ->whereNotNull('user_id');
                });
                
                // Include external establishments if requested and enabled in settings
                if ($includeExternal) {
                    $q->orWhere('is_external', true);
                }
            });
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('formatted_address', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('rating_min')) {
            $query->where('rating', '>=', (float) $request->input('rating_min'));
        }

        if ($request->filled('price_level')) {
            $query->where('price_level', (int) $request->input('price_level'));
        }

        if ($request->filled('amenities') && is_array($request->amenities)) {
            foreach ($request->amenities as $amenity) {
                $query->whereJsonContains('amenities', $amenity);
            }
        }

        // Handle proximity search
        if ($request->has('latitude') && $request->has('longitude')) {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $radius = $request->get('radius', 10);
            
            $query->selectRaw("id, name, slug, address, formatted_address, latitude, longitude, category, rating, is_external, is_merged, external_source, user_ratings_total, formatted_phone_number, business_status, price_level, photos, photo_urls,
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
                [$latitude, $longitude, $latitude])
                ->whereRaw("(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) < ?",
                    [$latitude, $longitude, $latitude, $radius])
                ->orderByRaw("(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))",
                    [$latitude, $longitude, $latitude]);
        } else {
            $query->select([
                'id', 'name', 'slug', 'address', 'formatted_address', 'latitude', 'longitude', 'category', 
                'rating', 'is_external', 'is_merged', 'external_source', 'user_ratings_total', 
                'formatted_phone_number', 'business_status', 'price_level', 'photos', 'photo_urls'
            ]);
        }

        $establishments = $query->get();

        return response()->json([
            'success' => true,
            'establishments' => $establishments,
            'count' => $establishments->count()
        ]);
    }
}