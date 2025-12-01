<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Support\Str;

trait InteractsWithPromotions
{
    use HandlesMediaUploads;

    protected function preparePromotionPayload(array $data, ?Promotion $promotion = null): array
    {
        if (array_key_exists('banner_image', $data)) {
            $banner = $this->storeImage($data['banner_image'], 'promotions/banners');

            if ($promotion && $promotion->banner_image && $banner) {
                $this->deleteStoredFiles([$promotion->banner_image]);
            }

            $data['banner_image'] = $banner;
        } elseif ($promotion) {
            $data['banner_image'] = $promotion->banner_image;
        }

        if (array_key_exists('applicable_products', $data)) {
            $data['applicable_products'] = $this->filterAllowedProducts($data['applicable_products'] ?? []);
        } elseif ($promotion) {
            $data['applicable_products'] = $promotion->applicable_products;
        }

        $data['channels'] = array_values(array_filter($data['channels'] ?? []));

        if (!isset($data['status'])) {
            $data['status'] = $promotion->status ?? 'draft';
        }

        return $data;
    }

    protected function filterAllowedProducts(array $productIds): array
    {
        if (empty($productIds)) {
            return [];
        }

        return Product::whereIn('id', $productIds)
            ->whereHas('establishment', fn ($q) => $q->where('user_id', auth()->id()))
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    protected function generatePromotionSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'promotion';
        $slug = $base;
        $counter = 1;

        while (Promotion::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}












