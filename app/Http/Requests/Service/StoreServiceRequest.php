<?php

namespace App\Http\Requests\Service;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('tags'))) {
            $this->merge([
                'tags' => array_values(array_filter(array_map('trim', preg_split('/[,;\n]+/', $this->input('tags'))))),
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', Rule::in(Service::CATEGORIES)],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'setup_fee' => ['nullable', 'numeric', 'min:0'],
            'includes_meat' => ['boolean'],
            'includes_staff' => ['boolean'],
            'includes_equipment' => ['boolean'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:4096'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'addons' => ['nullable', 'array'],
            'addons.*.name' => ['required_with:addons', 'string', 'max:255'],
            'addons.*.price' => ['required_with:addons', 'numeric', 'min:0'],
            'service_hours' => ['nullable', 'array'],
            'service_hours.*.day' => ['required_with:service_hours', 'string', 'max:20'],
            'service_hours.*.start' => ['required_with:service_hours', 'date_format:H:i'],
            'service_hours.*.end' => ['required_with:service_hours', 'date_format:H:i'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}

