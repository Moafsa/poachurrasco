<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Service;
use Illuminate\Support\Str;

trait InteractsWithServices
{
    use HandlesMediaUploads;

    protected function prepareServicePayload(array $data, ?Service $service = null): array
    {
        if (array_key_exists('images', $data) && is_array($data['images'])) {
            if ($service && $data['images']) {
                $this->deleteStoredFiles($service->images);
            }

            $data['images'] = $this->storeImageCollection($data['images'], 'services/images');
        } elseif ($service && !array_key_exists('images', $data)) {
            $data['images'] = $service->images;
        }

        if (array_key_exists('addons', $data)) {
            $data['addons'] = array_values(array_filter($data['addons'] ?? [], fn ($addon) => !empty($addon['name'])));
        } elseif ($service) {
            $data['addons'] = $service->addons;
        }

        if (array_key_exists('service_hours', $data)) {
            $data['service_hours'] = array_values(array_filter($data['service_hours'] ?? [], function ($slot) {
                return !empty($slot['day']) && !empty($slot['start']) && !empty($slot['end']);
            }));
        } elseif ($service) {
            $data['service_hours'] = $service->service_hours;
        }

        if ($service && !array_key_exists('establishment_id', $data)) {
            $data['establishment_id'] = $service->establishment_id;
        }

        return $data;
    }

    protected function generateServiceSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'service';
        $slug = $base;
        $counter = 1;

        while (Service::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}












