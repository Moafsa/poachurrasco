<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\ApiResponses;
use App\Http\Controllers\Concerns\InteractsWithRecipes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Recipe\StoreRecipeRequest;
use App\Http\Requests\Recipe\UpdateRecipeRequest;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    use ApiResponses;
    use InteractsWithRecipes;

    public function index(Request $request): JsonResponse
    {
        $query = Recipe::with('establishment')->where(function ($query) {
            $query->whereHas('establishment', fn ($q) => $q->where('user_id', auth()->id()))
                ->orWhereNull('establishment_id');
        });

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->string('difficulty')->toString());
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->string('status')->toString() === 'published');
        }

        $recipes = $query->latest()->paginate($request->integer('per_page', 15))->withQueryString();

        return $this->paginatedResponse($recipes);
    }

    public function store(StoreRecipeRequest $request): JsonResponse
    {
        $data = $this->prepareRecipePayload($request->validated());
        $data['slug'] = $this->generateRecipeSlug($data['title']);

        $recipe = Recipe::create($data);

        return $this->createdResponse($recipe->load('establishment'));
    }

    public function show(Recipe $recipe): JsonResponse
    {
        $this->authorize('view', $recipe);

        return $this->successResponse($recipe->load('establishment'));
    }

    public function update(UpdateRecipeRequest $request, Recipe $recipe): JsonResponse
    {
        $data = $this->prepareRecipePayload($request->validated(), $recipe);

        if (isset($data['title']) && $data['title'] !== $recipe->title) {
            $data['slug'] = $this->generateRecipeSlug($data['title'], $recipe->id);
        }

        $recipe->update($data);

        return $this->successResponse($recipe->fresh('establishment'));
    }

    public function destroy(Recipe $recipe): JsonResponse
    {
        $this->authorize('delete', $recipe);
        $this->deleteStoredFiles($recipe->images);
        $recipe->delete();

        return $this->deletedResponse();
    }
}












