<?php

namespace App\Http\Requests\Promotion;

use App\Models\Promotion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePromotionRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('channels'))) {
            $this->merge([
                'channels' => array_values(array_filter(array_map('trim', preg_split('/[,;\n]+/', $this->input('channels'))))),
            ]);
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
                Rule::exists('establishments', 'id')->where(fn ($query) => $query->where('user_id', auth()->id())),
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'promotion_type' => ['required', Rule::in(Promotion::TYPES)],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'minimum_order_value' => ['nullable', 'numeric', 'min:0'],
            'promo_code' => ['nullable', 'string', 'max:50', 'unique:promotions,promo_code'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', Rule::in(Promotion::STATUSES)],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'applicable_products' => ['nullable', 'array'],
            'applicable_products.*' => ['integer', 'exists:products,id'],
            'channels' => ['nullable', 'array'],
            'channels.*' => ['string', 'max:100'],
            'is_stackable' => ['boolean'],
            'is_featured' => ['boolean'],
            'banner_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:4096'],
            'terms' => ['nullable', 'string'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}

