<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StorageService
{
    /**
     * Get the configured storage disk for media files
     */
    public function getMediaDisk(): string
    {
        return env('STORAGE_DISK', 'public');
    }

    /**
     * Store a single image file
     */
    public function storeImage(?UploadedFile $file, string $directory): ?string
    {
        if (!$file instanceof UploadedFile) {
            return null;
        }

        try {
            $disk = $this->getMediaDisk();
            $path = $file->store($directory, $disk);
            
            Log::info("Image stored successfully", [
                'path' => $path,
                'disk' => $disk,
                'directory' => $directory,
            ]);

            return $path;
        } catch (\Exception $e) {
            Log::error("Failed to store image", [
                'error' => $e->getMessage(),
                'directory' => $directory,
                'disk' => $this->getMediaDisk(),
            ]);

            // Fallback to public disk if cloud storage fails
            if ($this->getMediaDisk() !== 'public') {
                Log::warning("Falling back to public disk", ['original_disk' => $this->getMediaDisk()]);
                return $file->store($directory, 'public');
            }

            throw $e;
        }
    }

    /**
     * Store multiple image files
     */
    public function storeImageCollection(?array $files, string $directory): ?array
    {
        if (empty($files)) {
            return null;
        }

        $paths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $path = $this->storeImage($file, $directory);
                if ($path) {
                    $paths[] = $path;
                }
            }
        }

        return $paths ?: null;
    }

    /**
     * Delete stored files
     */
    public function deleteStoredFiles(?array $paths): void
    {
        if (empty($paths)) {
            return;
        }

        $disk = $this->getMediaDisk();

        foreach ($paths as $path) {
            try {
                Storage::disk($disk)->delete($path);
            } catch (\Exception $e) {
                Log::error("Failed to delete file", [
                    'path' => $path,
                    'disk' => $disk,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Delete a single stored file
     */
    public function deleteFile(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        $disk = $this->getMediaDisk();

        try {
            Storage::disk($disk)->delete($path);
        } catch (\Exception $e) {
            Log::error("Failed to delete file", [
                'path' => $path,
                'disk' => $disk,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get the URL for a stored file
     */
    public function getUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        try {
            $disk = $this->getMediaDisk();
            return Storage::disk($disk)->url($path);
        } catch (\Exception $e) {
            Log::error("Failed to get file URL", [
                'path' => $path,
                'disk' => $this->getMediaDisk(),
                'error' => $e->getMessage(),
            ]);

            // Fallback to public disk
            if ($this->getMediaDisk() !== 'public') {
                return Storage::disk('public')->url($path);
            }

            return null;
        }
    }

    /**
     * Check if a file exists
     */
    public function fileExists(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        try {
            $disk = $this->getMediaDisk();
            return Storage::disk($disk)->exists($path);
        } catch (\Exception $e) {
            Log::error("Failed to check file existence", [
                'path' => $path,
                'disk' => $this->getMediaDisk(),
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get file size in bytes
     */
    public function getFileSize(?string $path): ?int
    {
        if (empty($path)) {
            return null;
        }

        try {
            $disk = $this->getMediaDisk();
            return Storage::disk($disk)->size($path);
        } catch (\Exception $e) {
            Log::error("Failed to get file size", [
                'path' => $path,
                'disk' => $this->getMediaDisk(),
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}




