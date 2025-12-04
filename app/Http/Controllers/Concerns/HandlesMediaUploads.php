<?php

namespace App\Http\Controllers\Concerns;

use App\Services\StorageService;
use Illuminate\Http\UploadedFile;

trait HandlesMediaUploads
{
    /**
     * Get the storage service instance
     */
    protected function getStorageService(): StorageService
    {
        return app(StorageService::class);
    }

    protected function storeImage(?UploadedFile $file, string $directory): ?string
    {
        return $this->getStorageService()->storeImage($file, $directory);
    }

    /**
     * @param UploadedFile[]|null $files
     */
    protected function storeImageCollection(?array $files, string $directory): ?array
    {
        return $this->getStorageService()->storeImageCollection($files, $directory);
    }

    protected function deleteStoredFiles(?array $paths): void
    {
        $this->getStorageService()->deleteStoredFiles($paths);
    }

    protected function deleteFile(?string $path): void
    {
        $this->getStorageService()->deleteFile($path);
    }
}












