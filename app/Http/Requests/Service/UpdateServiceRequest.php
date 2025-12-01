<?php

namespace App\Http\Requests\Service;

class UpdateServiceRequest extends StoreServiceRequest
{
    public function authorize(): bool
    {
        $service = $this->route('service');

        return $service && $this->user()->can('update', $service);
    }

    public function rules(): array
    {
        return collect(parent::rules())->map(function ($rule) {
            return array_merge(['sometimes'], (array) $rule);
        })->toArray();
    }
}












