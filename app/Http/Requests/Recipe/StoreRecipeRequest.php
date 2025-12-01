<?php

namespace App\Http\Requests\Recipe;

use App\Models\Recipe;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRecipeRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $fieldsToSplit = ['ingredients', 'instructions', 'tips'];

        foreach ($fieldsToSplit as $field) {
            if (is_string($this->input($field))) {
                $this->merge([
                    $field => array_values(array_filter(array_map('trim', preg_split('/[\r\n]+/', $this->input($field))))),
                ]);
            }
        }

        if (is_string($this->input('nutrition_facts')) && $this->input('nutrition_facts') !== '') {
            $decoded = json_decode($this->input('nutrition_facts'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->merge(['nutrition_facts' => $decoded]);
            }
        }
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'establishment_id' => [
                'nullable',
                Rule::exists('establishments', 'id')->where(fn ($query) => $query->where('user_id', auth()->id())),
            ],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'category' => ['required', Rule::in(Recipe::CATEGORIES)],
            'difficulty' => ['required', Rule::in(Recipe::DIFFICULTIES)],
            'prep_time_minutes' => ['nullable', 'integer', 'min:0'],
            'cook_time_minutes' => ['nullable', 'integer', 'min:0'],
            'rest_time_minutes' => ['nullable', 'integer', 'min:0'],
            'servings' => ['required', 'integer', 'min:1'],
            'ingredients' => ['required', 'array', 'min:1'],
            'ingredients.*' => ['string', 'max:255'],
            'instructions' => ['required', 'array', 'min:1'],
            'instructions.*' => ['string'],
            'tips' => ['nullable', 'array'],
            'tips.*' => ['string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:4096'],
            'video_url' => ['nullable', 'url'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'view_count' => ['nullable', 'integer', 'min:0'],
            'favorite_count' => ['nullable', 'integer', 'min:0'],
            'nutrition_facts' => ['nullable', 'array'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}

