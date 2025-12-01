<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\ApiResponses;
use App\Http\Controllers\Concerns\InteractsWithPromotions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Promotion\StorePromotionRequest;
use App\Http\Requests\Promotion\UpdatePromotionRequest;
use App\Models\Promotion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    use ApiResponses;
    use InteractsWithPromotions;

    public function index(Request $request): JsonResponse
    {
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

        $promotions = $query->latest()->paginate($request->integer('per_page', 15))->withQueryString();

        return $this->paginatedResponse($promotions);
    }

    public function store(StorePromotionRequest $request): JsonResponse
    {
        $data = $this->preparePromotionPayload($request->validated());
        $data['slug'] = $this->generatePromotionSlug($data['title']);

        $promotion = Promotion::create($data);

        return $this->createdResponse($promotion->load('establishment'));
    }

    public function show(Promotion $promotion): JsonResponse
    {
        $this->authorize('view', $promotion);

        return $this->successResponse($promotion->load('establishment'));
    }

    public function update(UpdatePromotionRequest $request, Promotion $promotion): JsonResponse
    {
        $data = $this->preparePromotionPayload($request->validated(), $promotion);

        if (isset($data['title']) && $data['title'] !== $promotion->title) {
            $data['slug'] = $this->generatePromotionSlug($data['title'], $promotion->id);
        }

        $promotion->update($data);

        return $this->successResponse($promotion->fresh('establishment'));
    }

    public function destroy(Promotion $promotion): JsonResponse
    {
        $this->authorize('delete', $promotion);

        if ($promotion->banner_image) {
            $this->deleteStoredFiles([$promotion->banner_image]);
        }

        $promotion->delete();

        return $this->deletedResponse();
    }
}












