<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesMediaUploads
{
    protected function storeImage(?UploadedFile $file, string $directory): ?string
    {
        if (!$file instanceof UploadedFile) {
            return null;
        }

        return $file->store($directory, 'public');
    }

    /**
     * @param UploadedFile[]|null $files
     */
    protected function storeImageCollection(?array $files, string $directory): ?array
    {
        if (empty($files)) {
            return null;
        }

        $paths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $file->store($directory, 'public');
            }
        }

        return $paths ?: null;
    }

    protected function deleteStoredFiles(?array $paths): void
    {
        if (empty($paths)) {
            return;
        }

        foreach ($paths as $path) {
            Storage::disk('public')->delete($path);
        }
    }
}












