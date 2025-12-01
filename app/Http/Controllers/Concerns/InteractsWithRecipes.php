<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Recipe;
use Illuminate\Support\Str;

trait InteractsWithRecipes
{
    use HandlesMediaUploads;

    protected function prepareRecipePayload(array $data, ?Recipe $recipe = null): array
    {
        if (array_key_exists('images', $data) && is_array($data['images'])) {
            if ($recipe && $data['images']) {
                $this->deleteStoredFiles($recipe->images);
            }

            $data['images'] = $this->storeImageCollection($data['images'], 'recipes/images');
        } elseif ($recipe && !array_key_exists('images', $data)) {
            $data['images'] = $recipe->images;
        }

        foreach (['ingredients', 'instructions', 'tips'] as $field) {
            if (array_key_exists($field, $data) && is_array($data[$field])) {
                $data[$field] = array_values(array_filter($data[$field], fn ($item) => $item !== ''));
            } elseif ($recipe) {
                $data[$field] = $recipe->{$field};
            }
        }

        if ($recipe && !array_key_exists('establishment_id', $data)) {
            $data['establishment_id'] = $recipe->establishment_id;
        }

        if (array_key_exists('is_published', $data) && $data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if (is_string($data['nutrition_facts'] ?? null)) {
            $decoded = json_decode($data['nutrition_facts'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data['nutrition_facts'] = $decoded;
            }
        }

        return $data;
    }

    protected function generateRecipeSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'recipe';
        $slug = $base;
        $counter = 1;

        while (Recipe::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}












