<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Concerns\InteractsWithPromotions;
use App\Http\Requests\Promotion\StorePromotionRequest;
use App\Http\Requests\Promotion\UpdatePromotionRequest;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    use HandlesMediaUploads;
    use InteractsWithPromotions;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Promotion::class);

        $query = Promotion::with('establishment')
            ->whereHas('establishment', fn ($q) => $q->where('user_id', auth()->id()));

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('promo_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('promotion_type')) {
            $query->where('promotion_type', $request->string('promotion_type')->toString());
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $promotions = $query->latest()->paginate(15)->withQueryString();

        return view('dashboard.promotions.index', [
            'promotions' => $promotions,
            'filters' => $request->only(['search', 'status', 'promotion_type', 'featured']),
            'statuses' => Promotion::STATUSES,
            'types' => Promotion::TYPES,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Promotion::class);

        $user = auth()->user();
        $establishments = $user?->establishments()->pluck('name', 'id') ?? collect();
        $products = Product::whereHas('establishment', fn ($q) => $q->where('user_id', $user?->id))
            ->orderBy('name')
            ->get(['id', 'name', 'establishment_id']);

        return view('dashboard.promotions.create', [
            'statuses' => Promotion::STATUSES,
            'types' => Promotion::TYPES,
            'establishments' => $establishments,
            'products' => $products,
        ]);
    }

    public function store(StorePromotionRequest $request): RedirectResponse
    {
        $this->authorize('create', Promotion::class);

        $data = $this->preparePromotionPayload($request->validated());
        $data['slug'] = $this->generatePromotionSlug($data['title']);

        $promotion = Promotion::create($data);

        return redirect()
            ->route('promotions.show', $promotion)
            ->with('success', 'Promotion created successfully.');
    }

    public function show(Promotion $promotion): View
    {
        $this->authorize('view', $promotion);

        $promotion->load('establishment');

        $applicableProducts = collect();

        if (!empty($promotion->applicable_products)) {
            $applicableProducts = Product::whereIn('id', $promotion->applicable_products)
                ->whereHas('establishment', fn ($q) => $q->where('user_id', auth()->id()))
                ->get()
                ->keyBy('id');
        }

        return view('dashboard.promotions.show', [
            'promotion' => $promotion,
            'types' => Promotion::TYPES,
            'statuses' => Promotion::STATUSES,
            'applicableProducts' => $applicableProducts,
        ]);
    }

    public function edit(Promotion $promotion): View
    {
        $this->authorize('update', $promotion);

        $user = auth()->user();
        $establishments = $user?->establishments()->pluck('name', 'id') ?? collect();
        $products = Product::whereHas('establishment', fn ($q) => $q->where('user_id', $user?->id))
            ->orderBy('name')
            ->get(['id', 'name', 'establishment_id']);

        return view('dashboard.promotions.edit', [
            'promotion' => $promotion,
            'statuses' => Promotion::STATUSES,
            'types' => Promotion::TYPES,
            'establishments' => $establishments,
            'products' => $products,
        ]);
    }

    public function update(UpdatePromotionRequest $request, Promotion $promotion): RedirectResponse
    {
        $data = $this->preparePromotionPayload($request->validated(), $promotion);

        if (isset($data['title']) && $data['title'] !== $promotion->title) {
            $data['slug'] = $this->generatePromotionSlug($data['title'], $promotion->id);
        }

        $promotion->update($data);

        return redirect()
            ->route('promotions.show', $promotion)
            ->with('success', 'Promotion updated successfully.');
    }

    public function destroy(Promotion $promotion): RedirectResponse
    {
        $this->authorize('delete', $promotion);

        if ($promotion->banner_image) {
            $this->deleteStoredFiles([$promotion->banner_image]);
        }

        $promotion->delete();

        return redirect()
            ->route('promotions.index')
            ->with('success', 'Promotion removed successfully.');
    }

}

