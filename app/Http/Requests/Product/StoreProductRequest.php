<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('tags'))) {
            $this->merge([
                'tags' => array_values(array_filter(array_map('trim', preg_split('/[,;\n]+/', $this->input('tags'))))),
            ]);
        }

        if (is_string($this->input('videos'))) {
            $this->merge([
                'videos' => array_values(array_filter(array_map('trim', preg_split('/[\s,;]+/', $this->input('videos'))))),
            ]);
        }

        foreach (['ingredients', 'nutritional_info', 'allergens'] as $field) {
            if (is_string($this->input($field))) {
                $this->merge([
                    $field => array_values(array_filter(array_map('trim', preg_split('/[\r\n]+/', $this->input($field))))),
                ]);
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
                'required',
                Rule::exists('establishments', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                }),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', Rule::in(Product::CATEGORIES)],
            'subcategory' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'sku' => ['nullable', 'string', 'max:100'],
            'barcode' => ['nullable', 'string', 'max:100'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'dimensions' => ['nullable', 'array'],
            'dimensions.length' => ['nullable', 'numeric', 'min:0'],
            'dimensions.width' => ['nullable', 'numeric', 'min:0'],
            'dimensions.height' => ['nullable', 'numeric', 'min:0'],
            'brand' => ['nullable', 'string', 'max:255'],
            'origin' => ['nullable', 'string', 'max:255'],
            'ingredients' => ['nullable', 'array'],
            'nutritional_info' => ['nullable', 'array'],
            'allergens' => ['nullable', 'array'],
            'storage_instructions' => ['nullable', 'string'],
            'expiry_date' => ['nullable', 'date'],
            'is_digital' => ['boolean'],
            'is_service' => ['boolean'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'track_stock' => ['boolean'],
            'allow_backorder' => ['boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:4096'],
            'videos' => ['nullable', 'array'],
            'videos.*' => ['string', 'url'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'seo_score' => ['nullable', 'integer', 'between:0,100'],
            'view_count' => ['nullable', 'integer', 'min:0'],
            'purchase_count' => ['nullable', 'integer', 'min:0'],
            'rating' => ['nullable', 'numeric', 'between:0,5'],
            'review_count' => ['nullable', 'integer', 'min:0'],
        ];
    }
}

