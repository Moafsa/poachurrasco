<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\ApiResponses;
use App\Http\Controllers\Concerns\InteractsWithProducts;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponses;
    use InteractsWithProducts;

    public function index(Request $request): JsonResponse
    {
        $query = Product::with('establishment')
            ->whereHas('establishment', fn ($q) => $q->where('user_id', auth()->id()));

        if ($request->filled('search')) {
            $query->search($request->string('search')->toString());
        }

        if ($request->filled('category')) {
            $query->byCategory($request->string('category')->toString());
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->string('status')->toString() === 'active');
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
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

        $products = $query->paginate($request->integer('per_page', 15))->withQueryString();

        return $this->paginatedResponse($products);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $data = $this->prepareProductPayload($request->validated());

        $product = Product::create($data);
        $product->load('establishment');

        return $this->createdResponse($product);
    }

    public function show(Product $product): JsonResponse
    {
        $this->authorize('view', $product);

        return $this->successResponse($product->load('establishment'));
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $data = $this->prepareProductPayload($request->validated(), $product);
        $product->update($data);

        return $this->successResponse($product->fresh('establishment'));
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);
        $this->deleteStoredFiles($product->images);
        $product->delete();

        return $this->deletedResponse();
    }
}












