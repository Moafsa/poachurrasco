<?php

use Illuminate\Support\Facades\Storage;
use App\Services\StorageService;

if (!function_exists('storage_url')) {
    /**
     * Get the URL for a stored file using the configured storage disk
     *
     * @param string|null $path
     * @return string|null
     */
    function storage_url(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        $storageService = app(StorageService::class);
        return $storageService->getUrl($path);
    }
}

if (!function_exists('storage_disk')) {
    /**
     * Get the configured storage disk name
     *
     * @return string
     */
    function storage_disk(): string
    {
        $storageService = app(StorageService::class);
        return $storageService->getMediaDisk();
    }
}


















