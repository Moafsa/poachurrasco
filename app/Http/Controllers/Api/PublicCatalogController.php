<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\ApiResponses;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicCatalogController extends Controller
{
    use ApiResponses;

    public function products(Request $request): JsonResponse
    {
        $query = Product::with('establishment')
            ->active()
            ->whereHas('establishment', fn ($builder) => $builder->active());

        $category = $request->string('category')->toString();
        if ($category && in_array($category, Product::CATEGORIES, true)) {
            $query->where('category', $category);
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

        $products = $query->paginate($request->integer('per_page', 12))->withQueryString();

        return $this->paginatedResponse($products);
    }

    public function promotions(Request $request): JsonResponse
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
            ->paginate($request->integer('per_page', 12))
            ->withQueryString();

        return $this->paginatedResponse($promotions);
    }

    public function services(Request $request): JsonResponse
    {
        $query = Service::with('establishment')
            ->active()
            ->whereHas('establishment', fn ($builder) => $builder->active());

        $category = $request->string('category')->toString();
        if ($category && in_array($category, Service::CATEGORIES, true)) {
            $query->where('category', $category);
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $services = $query->orderByDesc('is_featured')
            ->orderBy('price')
            ->paginate($request->integer('per_page', 12))
            ->withQueryString();

        return $this->paginatedResponse($services);
    }
}











