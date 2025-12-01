<?php

namespace App\Http\Requests\Promotion;

use Illuminate\Validation\Rule;

class UpdatePromotionRequest extends StorePromotionRequest
{
    public function authorize(): bool
    {
        $promotion = $this->route('promotion');

        return $promotion && $this->user()->can('update', $promotion);
    }

    public function rules(): array
    {
        $promotionId = $this->route('promotion')?->id;
        $rules = parent::rules();

        $rules['promo_code'] = [
            'sometimes',
            'nullable',
            'string',
            'max:50',
            Rule::unique('promotions', 'promo_code')->ignore($promotionId),
        ];

        return collect($rules)->map(function ($rule, $attribute) {
            if (in_array('required', (array) $rule, true)) {
                return array_merge(['sometimes'], (array) $rule);
            }

            return array_merge(['sometimes'], (array) $rule);
        })->toArray();
    }
}












