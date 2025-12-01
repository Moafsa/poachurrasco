<?php

namespace App\Http\Requests\Video;

use App\Models\Video;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVideoRequest extends FormRequest
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
                'nullable',
                Rule::exists('establishments', 'id')->where(fn ($query) => $query->where('user_id', auth()->id())),
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', Rule::in(Video::CATEGORIES)],
            'video_url' => ['required', 'url'],
            'provider' => ['nullable', 'string', 'max:50'],
            'provider_video_id' => ['nullable', 'string', 'max:100'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'thumbnail_url' => ['nullable', 'url'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'view_count' => ['nullable', 'integer', 'min:0'],
            'like_count' => ['nullable', 'integer', 'min:0'],
            'share_count' => ['nullable', 'integer', 'min:0'],
            'captions' => ['nullable', 'array'],
            'captions.*.language' => ['required_with:captions', 'string', 'max:10'],
            'captions.*.url' => ['required_with:captions', 'url'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}

