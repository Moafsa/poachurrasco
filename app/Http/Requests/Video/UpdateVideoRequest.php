<?php

namespace App\Http\Requests\Video;

class UpdateVideoRequest extends StoreVideoRequest
{
    public function authorize(): bool
    {
        $video = $this->route('video');

        return $video && $this->user()->can('update', $video);
    }

    public function rules(): array
    {
        return collect(parent::rules())->map(function ($rule) {
            return array_merge(['sometimes'], (array) $rule);
        })->toArray();
    }
}












