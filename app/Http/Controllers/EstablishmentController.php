<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Establishment;
use App\Services\EstablishmentApiService;
use App\Jobs\ProcessEstablishmentJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
    public function proxyPhoto($id)
    {
        $establishment = Establishment::findOrFail($id);
        
        if (!$establishment->photo_urls || count($establishment->photo_urls) === 0) {
            return response()->file(public_path('images/placeholder.jpg'));
        }
        
        try {
            $photoUrl = $establishment->photo_urls[0];
            $image = file_get_contents($photoUrl);
            
            if ($image === false) {
                return response()->file(public_path('images/placeholder.jpg'));
            }
            
            return response($image, 200)->header('Content-Type', 'image/jpeg');
        } catch (\Exception $e) {
            Log::error('Error proxying photo: ' . $e->getMessage());
            return response()->file(public_path('images/placeholder.jpg'));
        }
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
        $query = Establishment::active();
        
        // Include both user-created and external establishments
        if ($request->has('include_external') && $request->include_external) {
            // Include all active establishments
        } else {
            $query->where('user_id', auth()->id());
        }

        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        // Handle proximity search
        if ($request->has('latitude') && $request->has('longitude')) {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $radius = $request->get('radius', 10);
            
            $query->selectRaw("id, name, address, formatted_address, latitude, longitude, category, rating, is_external, is_merged, external_source, user_ratings_total, formatted_phone_number, business_status, price_level, photos, photo_urls,
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
                [$latitude, $longitude, $latitude])
                ->whereRaw("(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) < ?",
                    [$latitude, $longitude, $latitude, $radius])
                ->orderByRaw("(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))",
                    [$latitude, $longitude, $latitude]);
        } else {
            $query->select([
                'id', 'name', 'address', 'formatted_address', 'latitude', 'longitude', 'category', 
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