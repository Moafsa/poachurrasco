<?php

namespace App\Http\Requests\Recipe;

class UpdateRecipeRequest extends StoreRecipeRequest
{
    public function authorize(): bool
    {
        $recipe = $this->route('recipe');

        return $recipe && $this->user()->can('update', $recipe);
    }

    public function rules(): array
    {
        return collect(parent::rules())->map(function ($rule) {
            return array_merge(['sometimes'], (array) $rule);
        })->toArray();
    }
}












