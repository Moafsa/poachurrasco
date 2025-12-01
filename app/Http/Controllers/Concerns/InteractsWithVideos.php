<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Video;
use Illuminate\Support\Str;

trait InteractsWithVideos
{
    protected function prepareVideoPayload(array $data, ?Video $video = null): array
    {
        if (array_key_exists('captions', $data)) {
            $data['captions'] = array_values(array_filter($data['captions'] ?? [], fn ($caption) => !empty($caption['url'])));
        } elseif ($video) {
            $data['captions'] = $video->captions;
        }

        if (!array_key_exists('establishment_id', $data) && $video) {
            $data['establishment_id'] = $video->establishment_id;
        }

        if (array_key_exists('is_published', $data) && $data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return $data;
    }

    protected function generateVideoSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'video';
        $slug = $base;
        $counter = 1;

        while (Video::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}












