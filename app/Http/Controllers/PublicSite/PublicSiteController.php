<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Establishment;
use App\Models\HeroSection;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class PublicSiteController extends Controller
{
    /**
     * Display the public landing page populated with live data.
     */
    public function home()
    {
        // Check if external establishments should be shown
        try {
            $showExternal = \App\Models\SystemSetting::showExternalEstablishments();
        } catch (\Exception $e) {
            // Fallback if SystemSetting model is not available
            $showExternal = true;
        }
        
        // First, get featured establishments (user-created + external if enabled)
        $featuredQuery = Establishment::query()
            ->where(function ($q) use ($showExternal) {
                // Include active user-created establishments
                $q->where(function ($subQ) {
                    $subQ->where('status', 'active')
                         ->whereNotNull('user_id')
                         ->where('is_featured', true);
                });
                
                // Include external establishments if enabled in settings
                if ($showExternal) {
                    $q->orWhere(function ($subQ) {
                        $subQ->where('is_external', true)
                             ->where('is_featured', true);
                    });
                }
            })
            ->with(['products' => fn ($query) => $query->active()->take(3)])
            ->orderByDesc('rating');
        
        $featuredEstablishments = $featuredQuery->get();
        
        // If we don't have 6 establishments, complement with external non-featured establishments
        // Priority: external churrascarias with photos > external churrascarias without photos > other external with photos > others
        $targetCount = 6;
        if ($featuredEstablishments->count() < $targetCount && $showExternal) {
            $remainingCount = $targetCount - $featuredEstablishments->count();
            $externalIds = $featuredEstablishments->pluck('id')->toArray();
            
            // Get all potential external establishments with products relationship
            $allExternals = Establishment::query()
                ->where('is_external', true)
                ->whereNotIn('id', $externalIds)
                ->whereNotNull('rating')
                ->with(['products' => fn ($query) => $query->active()->take(3)])
                ->get();
            
            // Helper function to check if establishment has photos
            $hasPhotos = function ($est) {
                return !empty($est->photo_urls) && is_array($est->photo_urls) && count($est->photo_urls) > 0;
            };
            
            // Helper function to calculate priority score for sorting
            $calculatePriority = function ($est) use ($hasPhotos) {
                $priority = 0;
                $hasPhoto = $hasPhotos($est);
                
                // Higher priority numbers = higher priority
                // Priority order: 1) Churrascarias with photos, 2) Churrascarias without photos, 
                // 3) Others with photos, 4) Others without photos
                if ($est->category === 'churrascaria' && $hasPhoto) {
                    $priority = 4000; // Highest priority
                } elseif ($est->category === 'churrascaria' && !$hasPhoto) {
                    $priority = 3000;
                } elseif ($hasPhoto) {
                    $priority = 2000;
                } else {
                    $priority = 1000;
                }
                
                // Add rating and review count to priority score for fine-tuning
                $priority += ($est->rating ?? 0) * 100;
                $priority += ($est->user_ratings_total ?? 0) * 0.1;
                
                return $priority;
            };
            
            // Separate and prioritize by creating a sorted collection
            $prioritized = $allExternals->map(function ($est) use ($calculatePriority) {
                return [
                    'establishment' => $est,
                    'priority' => $calculatePriority($est),
                ];
            })
            ->filter(function ($item) {
                // Only include establishments with rating >= 3.0 initially
                return ($item['establishment']->rating ?? 0) >= 3.0;
            })
            ->sortByDesc('priority')
            ->map(function ($item) {
                return $item['establishment'];
            });
            
            $additionalExternals = $prioritized->take($remainingCount);
            
            // If we still don't have enough, get any external establishments with rating >= 2.0
            if ($additionalExternals->count() < $remainingCount) {
                $stillNeeded = $remainingCount - $additionalExternals->count();
                $alreadyIncludedIds = $additionalExternals->pluck('id')->toArray();
                
                $moreExternals = $allExternals
                    ->filter(function ($est) use ($alreadyIncludedIds) {
                        return !in_array($est->id, $alreadyIncludedIds) && ($est->rating ?? 0) >= 2.0;
                    })
                    ->sortByDesc($calculatePriority)
                    ->take($stillNeeded);
                
                $additionalExternals = $additionalExternals->merge($moreExternals);
            }
            
            $featuredEstablishments = $featuredEstablishments->merge($additionalExternals)->take($targetCount);
        } else {
            $featuredEstablishments = $featuredEstablishments->take($targetCount);
        }

        $highlightProducts = Product::with('establishment')
            ->active()
            ->orderByDesc('is_featured')
            ->orderByDesc('view_count')
            ->take(8)
            ->get();

        $highlightPromotions = Promotion::with('establishment')
            ->active()
            ->orderByDesc('is_featured')
            ->orderBy('starts_at')
            ->take(6)
            ->get();

        $highlightServices = Service::with('establishment')
            ->active()
            ->orderByDesc('is_featured')
            ->orderBy('price')
            ->take(6)
            ->get();

        // Calculate metrics including external if enabled
        $establishmentsCountQuery = Establishment::query()
            ->where(function ($q) use ($showExternal) {
                $q->where('status', 'active')
                  ->whereNotNull('user_id');
                
                if ($showExternal) {
                    $q->orWhere('is_external', true);
                }
            });
        
        $metrics = [
            'establishments_count' => $establishmentsCountQuery->count(),
            'top_rating' => round($establishmentsCountQuery->max('rating') ?? 0, 1),
            'products_count' => Product::active()->count(),
        ];

        // Get hero section for home page with media loaded
        try {
            $heroSection = HeroSection::where('page', 'home')
                ->where('is_active', true)
                ->with('media')
                ->orderBy('display_order')
                ->first();
        } catch (\Exception $e) {
            // If there's an error (e.g., table doesn't exist), set to null
            Log::warning('Error loading hero section: ' . $e->getMessage());
            $heroSection = null;
        }

        return view('public.home', [
            'metrics' => $metrics,
            'featuredEstablishments' => $featuredEstablishments,
            'highlightProducts' => $highlightProducts,
            'highlightPromotions' => $highlightPromotions,
            'highlightServices' => $highlightServices,
            'heroSection' => $heroSection,
        ]);
    }

    /**
     * Render the interactive map page.
     */
    public function map()
    {
        try {
            $categories = Establishment::query()
                ->select('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category')
                ->filter()
                ->values();
        } catch (\Exception $e) {
            Log::error('Error fetching categories for map: ' . $e->getMessage());
            $categories = collect();
        }

        return view('public.mapa', compact('categories'));
    }

    /**
     * Render the unified search page.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $results = collect();

        if (! empty($query)) {
            $results = $this->performSearch($query);
        }

        return view('public.search', [
            'query' => $query,
            'results' => $results,
        ]);
    }

    /**
     * Display the marketplace page with live product data.
     */
    public function products(Request $request)
    {
        $query = Product::with('establishment')
            ->active()
            ->whereHas('establishment', fn ($builder) => $builder->active());

        $selectedCategory = $request->string('category')->toString();
        if ($selectedCategory && in_array($selectedCategory, Product::CATEGORIES, true)) {
            $query->where('category', $selectedCategory);
        }

        $sort = $request->string('sort', 'popular')->toString();
        switch ($sort) {
            case 'price-low':
                $query->orderBy('price');
                break;
            case 'price-high':
                $query->orderByDesc('price');
                break;
            case 'rating':
                $query->orderByDesc('rating');
                break;
            case 'newest':
                $query->latest();
                break;
            default:
                $query->orderByDesc('is_featured')
                    ->orderByDesc('purchase_count')
                    ->orderByDesc('view_count');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        return view('public.products', [
            'products' => $products,
            'categories' => Product::CATEGORIES,
            'selectedCategory' => $selectedCategory,
            'selectedSort' => $sort,
        ]);
    }

    /**
     * Display public promotions.
     */
    public function promotions(Request $request)
    {
        $query = Promotion::with('establishment')
            ->active()
            ->whereHas('establishment', fn ($builder) => $builder->active());

        if ($request->filled('type')) {
            $query->where('promotion_type', $request->string('type')->toString());
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $promotions = $query->orderBy('starts_at')
            ->paginate(12)
            ->withQueryString();

        return view('public.promotions', compact('promotions'));
    }

    /**
     * Display public services.
     */
    public function services(Request $request)
    {
        $query = Service::with('establishment')
            ->active()
            ->whereHas('establishment', fn ($builder) => $builder->active());

        $selectedCategory = $request->string('category')->toString();
        if ($selectedCategory && in_array($selectedCategory, Service::CATEGORIES, true)) {
            $query->where('category', $selectedCategory);
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $services = $query->orderByDesc('is_featured')
            ->orderBy('price')
            ->paginate(12)
            ->withQueryString();

        return view('public.services', [
            'services' => $services,
            'categories' => Service::CATEGORIES,
            'selectedCategory' => $selectedCategory,
        ]);
    }

    /**
     * Display a public establishment landing page by slug.
     */
    public function establishment($slug)
    {
        try {
            $establishment = Establishment::where('slug', $slug)->first();
            
            if (!$establishment) {
                abort(404);
            }
            
            // Load relationships safely - only if they exist and won't cause errors
            try {
                if (method_exists($establishment, 'products')) {
                    $establishment->load(['products' => fn ($query) => $query->where('is_active', true)->orderByDesc('is_featured')->take(8)]);
                }
            } catch (\Exception $e) {
                $establishment->setRelation('products', collect([]));
            }
            
            try {
                if (method_exists($establishment, 'promotions')) {
                    $establishment->load(['promotions' => fn ($query) => $query->where('is_active', true)->where('end_date', '>=', now())->orderByDesc('is_featured')->take(4)]);
                }
            } catch (\Exception $e) {
                $establishment->setRelation('promotions', collect([]));
            }
            
            try {
                if (method_exists($establishment, 'services')) {
                    $establishment->load(['services' => fn ($query) => $query->where('is_active', true)->orderByDesc('is_featured')->take(4)]);
                }
            } catch (\Exception $e) {
                $establishment->setRelation('services', collect([]));
            }
            
            try {
                if (method_exists($establishment, 'recipes')) {
                    $establishment->load(['recipes' => fn ($query) => $query->where('is_published', true)->orderByDesc('is_featured')->take(4)]);
                }
            } catch (\Exception $e) {
                $establishment->setRelation('recipes', collect([]));
            }
            
            try {
                if (method_exists($establishment, 'videos')) {
                    $establishment->load(['videos' => fn ($query) => $query->where('is_published', true)->orderByDesc('is_featured')->take(4)]);
                }
            } catch (\Exception $e) {
                $establishment->setRelation('videos', collect([]));
            }
            
            return view('public.establishment-details', compact('establishment'));
            
        } catch (\Throwable $e) {
            Log::error('Error in establishment: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            
            if (config('app.debug')) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ], 500);
            }
            
            abort(500);
        }
    }

    /**
     * Perform the unified search across public modules.
     */
    protected function performSearch(string $query)
    {
        $establishments = Establishment::query()
            ->active()
            ->verified()
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('formatted_address', 'like', "%{$query}%");
            })
            ->take(10)
            ->get()
            ->map(fn ($establishment) => [
                'type' => 'establishment',
                'title' => $establishment->name,
                'description' => $establishment->description,
                'rating' => $establishment->rating,
                'meta' => [
                    'category' => $establishment->category,
                    'address' => $establishment->formatted_address ?? $establishment->address,
                ],
                'url' => route('public.establishment', $establishment),
            ]);

        $productsResults = Product::query()
            ->where('is_active', true)
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('tags', 'like', "%{$query}%");
            })
            ->take(10)
            ->get()
            ->map(fn ($product) => [
                'type' => 'product',
                'title' => $product->name,
                'description' => $product->description,
                'rating' => $product->rating,
                'meta' => [
                    'price' => $product->price,
                    'category' => $product->category,
                ],
                'url' => route('products'),
            ]);

        return $establishments
            ->merge($productsResults)
            ->sortByDesc(fn ($item) => $item['rating'] ?? 0)
            ->values();
    }
}

