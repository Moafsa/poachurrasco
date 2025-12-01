<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Concerns\InteractsWithProducts;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use HandlesMediaUploads;
    use InteractsWithProducts;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Product::class);

        $query = Product::with('establishment')
            ->whereHas('establishment', fn ($q) => $q->where('user_id', auth()->id()));

        if ($request->filled('search')) {
            $query->search($request->string('search')->toString());
        }

        if ($request->filled('category')) {
            $query->byCategory($request->string('category')->toString());
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->string('status')->toString() === 'active');
        }

        if ($request->filled('sort')) {
            $sort = $request->string('sort')->toString();
            if (in_array($sort, ['price', 'name', 'view_count', 'purchase_count'], true)) {
                $direction = $request->string('direction', 'asc')->toString() === 'desc' ? 'desc' : 'asc';
                $query->orderBy($sort, $direction);
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(15)->withQueryString();

        return view('dashboard.products.index', [
            'products' => $products,
            'categories' => Product::CATEGORIES,
            'filters' => $request->only(['search', 'category', 'featured', 'status', 'sort', 'direction']),
        ]);
    }

    public function analytics(Request $request): View
    {
        $this->authorize('viewAny', Product::class);

        $baseQuery = Product::with('establishment')
            ->whereHas('establishment', fn ($q) => $q->where('user_id', auth()->id()));

        $aggregates = (clone $baseQuery)->selectRaw('
            COUNT(*) as total_products,
            SUM(CASE WHEN track_stock THEN stock_quantity ELSE 0 END) as total_units,
            SUM(CASE WHEN track_stock THEN price * stock_quantity ELSE 0 END) as stock_value,
            SUM(price * purchase_count) as revenue,
            SUM(view_count) as total_views,
            AVG(price) as average_price
        ')->first();

        $metrics = [
            'total_products' => (int) ($aggregates->total_products ?? 0),
            'total_units' => (int) ($aggregates->total_units ?? 0),
            'stock_value' => (float) ($aggregates->stock_value ?? 0),
            'revenue' => (float) ($aggregates->revenue ?? 0),
            'total_views' => (int) ($aggregates->total_views ?? 0),
            'average_price' => (float) ($aggregates->average_price ?? 0),
            'featured_products' => (clone $baseQuery)->where('is_featured', true)->count(),
            'active_products' => (clone $baseQuery)->where('is_active', true)->count(),
            'inactive_products' => (clone $baseQuery)->where('is_active', false)->count(),
            'low_stock_products' => (clone $baseQuery)
                ->where('track_stock', true)
                ->where(function ($query) {
                    $query->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                        ->orWhere('stock_quantity', '<=', 0);
                })
                ->count(),
        ];

        $topSellers = (clone $baseQuery)
            ->orderByDesc('purchase_count')
            ->take(5)
            ->get();

        $topViewed = (clone $baseQuery)
            ->orderByDesc('view_count')
            ->take(5)
            ->get();

        $lowStockProducts = (clone $baseQuery)
            ->where('track_stock', true)
            ->where(function ($query) {
                $query->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                    ->orWhere('stock_quantity', '<=', 0);
            })
            ->orderBy('stock_quantity')
            ->take(5)
            ->get();

        $categoryBreakdown = (clone $baseQuery)
            ->selectRaw('category, COUNT(*) as total, SUM(view_count) as views, SUM(purchase_count) as purchases, SUM(price * purchase_count) as revenue')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $recentProducts = (clone $baseQuery)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.products.analytics', [
            'metrics' => $metrics,
            'topSellers' => $topSellers,
            'topViewed' => $topViewed,
            'lowStockProducts' => $lowStockProducts,
            'categoryBreakdown' => $categoryBreakdown,
            'recentProducts' => $recentProducts,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Product::class);

        $establishments = auth()->user()?->establishments()->pluck('name', 'id') ?? collect();

        return view('dashboard.products.create', [
            'categories' => Product::CATEGORIES,
            'establishments' => $establishments,
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->authorize('create', Product::class);

        $data = $this->prepareProductPayload($request->validated());

        $product = Product::create($data);

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product): View
    {
        $this->authorize('view', $product);

        $product->load('establishment');
        $relatedPromotions = Promotion::where('establishment_id', $product->establishment_id)
            ->active()
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.products.show', [
            'product' => $product,
            'relatedPromotions' => $relatedPromotions,
        ]);
    }

    public function edit(Product $product): View
    {
        $this->authorize('update', $product);

        $establishments = auth()->user()?->establishments()->pluck('name', 'id') ?? collect();

        return view('dashboard.products.edit', [
            'product' => $product,
            'categories' => Product::CATEGORIES,
            'establishments' => $establishments,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $this->prepareProductPayload($request->validated(), $product);

        $product->update($data);

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        $this->deleteStoredFiles($product->images);
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product removed successfully.');
    }

}
