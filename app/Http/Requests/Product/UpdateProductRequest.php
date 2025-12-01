<?php

namespace App\Http\Requests\Product;

class UpdateProductRequest extends StoreProductRequest
{
    public function authorize(): bool
    {
        $product = $this->route('product');

        return $product && $this->user()->can('update', $product);
    }

    public function rules(): array
    {
        $rules = parent::rules();

        return collect($rules)->map(function ($rule, $attribute) {
            if ($attribute === 'establishment_id') {
                return array_merge(['sometimes'], (array) $rule);
            }

            return array_merge(['sometimes'], (array) $rule);
        })->toArray();
    }
}

