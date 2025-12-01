<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Product;
use Illuminate\Support\Arr;

trait InteractsWithProducts
{
    use HandlesMediaUploads;

    protected function prepareProductPayload(array $data, ?Product $product = null): array
    {
        if (array_key_exists('images', $data) && is_array($data['images'])) {
            if ($product && $data['images']) {
                $this->deleteStoredFiles($product->images);
            }

            $data['images'] = $this->storeImageCollection($data['images'], 'products/images');
        } elseif ($product && !array_key_exists('images', $data)) {
            $data['images'] = $product->images;
        }

        if (array_key_exists('dimensions', $data)) {
            $data['dimensions'] = Arr::where(
                $data['dimensions'] ?? [],
                fn ($value) => $value !== null && $value !== ''
            );
        }

        if ($product && !array_key_exists('establishment_id', $data)) {
            $data['establishment_id'] = $product->establishment_id;
        }

        return $data;
    }
}












