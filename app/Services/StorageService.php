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
        $disk = env('STORAGE_DISK', 'public');
        Log::info("Using storage disk: {$disk}");
        return $disk;
    }

    /**
     * Store a single image file
     */
    public function storeImage(?UploadedFile $file, string $directory): ?string
    {
        Log::info('=== StorageService::storeImage called ===', [
            'file_is_null' => is_null($file),
            'file_is_uploaded_file' => $file instanceof UploadedFile,
            'directory' => $directory,
        ]);
        
        if (!$file instanceof UploadedFile) {
            Log::error('File is not an UploadedFile instance', [
                'file_type' => gettype($file),
                'file_class' => is_object($file) ? get_class($file) : 'not_object',
            ]);
            return null;
        }

        $configuredDisk = $this->getMediaDisk();
        Log::info('Using disk', ['disk' => $configuredDisk]);
        
        // Se for MinIO, tenta primeiro, mas se falhar, usa public imediatamente
        if ($configuredDisk === 'minio') {
            try {
                Log::info("Attempting to store image in MinIO", [
                    'directory' => $directory,
                    'file_size' => $file->getSize(),
                    'file_name' => $file->getClientOriginalName(),
                ]);
                
                // Timeout de 10 segundos para MinIO
                $path = $file->store($directory, 'minio');
                
                Log::info("Image stored successfully in MinIO", [
                    'path' => $path,
                    'disk' => 'minio',
                ]);

                return $path;
            } catch (\Exception $e) {
                Log::error("Failed to store image in MinIO, falling back to public", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                // Fallback imediato para public disk
                try {
                    $path = $file->store($directory, 'public');
                    Log::info("Image stored successfully in public disk (fallback)", [
                        'path' => $path,
                    ]);
                    return $path;
                } catch (\Exception $e2) {
                    Log::error("Failed to store image in public disk too", [
                        'error' => $e2->getMessage(),
                    ]);
                    throw $e2;
                }
            }
        }

        // Para outros discos (public, etc)
        try {
            Log::info('Attempting to store file', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'directory' => $directory,
                'disk' => $configuredDisk,
            ]);
            
            $path = $file->store($directory, $configuredDisk);
            
            Log::info("Image stored successfully", [
                'path' => $path,
                'disk' => $configuredDisk,
                'directory' => $directory,
                'full_path' => Storage::disk($configuredDisk)->path($path),
            ]);
            
            // Verificar se o arquivo realmente existe
            if (Storage::disk($configuredDisk)->exists($path)) {
                Log::info('File confirmed to exist after storage', [
                    'path' => $path,
                    'size' => Storage::disk($configuredDisk)->size($path),
                ]);
            } else {
                Log::error('File does not exist after storage!', ['path' => $path]);
            }

            return $path;
        } catch (\Exception $e) {
            Log::error("Failed to store image", [
                'error' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'directory' => $directory,
                'disk' => $configuredDisk,
            ]);

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












