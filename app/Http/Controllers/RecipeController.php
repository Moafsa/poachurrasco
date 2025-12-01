<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Concerns\InteractsWithRecipes;
use App\Http\Requests\Recipe\StoreRecipeRequest;
use App\Http\Requests\Recipe\UpdateRecipeRequest;
use App\Models\Recipe;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    use HandlesMediaUploads;
    use InteractsWithRecipes;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Recipe::class);

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

        $recipes = $query->latest()->paginate(15)->withQueryString();

        return view('dashboard.recipes.index', [
            'recipes' => $recipes,
            'categories' => Recipe::CATEGORIES,
            'difficulties' => Recipe::DIFFICULTIES,
            'filters' => $request->only(['search', 'category', 'difficulty', 'featured', 'status']),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Recipe::class);

        $establishments = auth()->user()?->establishments()->pluck('name', 'id') ?? collect();

        return view('dashboard.recipes.create', [
            'categories' => Recipe::CATEGORIES,
            'difficulties' => Recipe::DIFFICULTIES,
            'establishments' => $establishments,
        ]);
    }

    public function store(StoreRecipeRequest $request): RedirectResponse
    {
        $this->authorize('create', Recipe::class);

        $data = $this->prepareRecipePayload($request->validated());
        $data['slug'] = $this->generateRecipeSlug($data['title']);

        $recipe = Recipe::create($data);

        return redirect()
            ->route('recipes.show', $recipe)
            ->with('success', 'Recipe created successfully.');
    }

    public function show(Recipe $recipe): View
    {
        $this->authorize('view', $recipe);

        $recipe->load('establishment');

        return view('dashboard.recipes.show', [
            'recipe' => $recipe,
            'categories' => Recipe::CATEGORIES,
            'difficulties' => Recipe::DIFFICULTIES,
        ]);
    }

    public function edit(Recipe $recipe): View
    {
        $this->authorize('update', $recipe);

        $establishments = auth()->user()?->establishments()->pluck('name', 'id') ?? collect();

        return view('dashboard.recipes.edit', [
            'recipe' => $recipe,
            'categories' => Recipe::CATEGORIES,
            'difficulties' => Recipe::DIFFICULTIES,
            'establishments' => $establishments,
        ]);
    }

    public function update(UpdateRecipeRequest $request, Recipe $recipe): RedirectResponse
    {
        $data = $this->prepareRecipePayload($request->validated(), $recipe);

        if (isset($data['title']) && $data['title'] !== $recipe->title) {
            $data['slug'] = $this->generateRecipeSlug($data['title'], $recipe->id);
        }

        $recipe->update($data);

        return redirect()
            ->route('recipes.show', $recipe)
            ->with('success', 'Recipe updated successfully.');
    }

    public function destroy(Recipe $recipe): RedirectResponse
    {
        $this->authorize('delete', $recipe);

        $this->deleteStoredFiles($recipe->images);
        $recipe->delete();

        return redirect()
            ->route('recipes.index')
            ->with('success', 'Recipe removed successfully.');
    }

}

