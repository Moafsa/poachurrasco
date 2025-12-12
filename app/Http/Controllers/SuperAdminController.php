<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Models\HeroMedia;
use App\Models\HeroSection;
use App\Models\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SuperAdminController extends Controller
{
    use HandlesMediaUploads;

    /**
     * Display super admin dashboard
     */
    public function index(): View
    {
        $heroSections = HeroSection::with('media')->orderBy('page')->get();
        $siteContents = SiteContent::orderBy('page')->orderBy('section')->get();
        
        return view('dashboard.super-admin.index', [
            'heroSections' => $heroSections,
            'siteContents' => $siteContents,
        ]);
    }

    /**
     * Show site content management
     */
    public function content(): View
    {
        $contents = SiteContent::orderBy('page')->orderBy('section')->get();
        
        return view('dashboard.super-admin.content', [
            'contents' => $contents,
        ]);
    }

    /**
     * Store or update site content
     */
    public function storeContent(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'required|string|in:text,html,image',
            'page' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
        ]);

        SiteContent::updateOrCreate(
            ['key' => $validated['key']],
            $validated
        );

        return redirect()
            ->route('super-admin.content')
            ->with('success', 'Content saved successfully!');
    }

    /**
     * Delete site content
     */
    public function deleteContent(SiteContent $content): RedirectResponse
    {
        $content->delete();

        return redirect()
            ->route('super-admin.content')
            ->with('success', 'Content deleted successfully!');
    }

    /**
     * Show hero sections management
     */
    public function heroSections(): View
    {
        $heroSections = HeroSection::with('media')->orderBy('page')->get();
        
        return view('dashboard.super-admin.hero-sections', [
            'heroSections' => $heroSections,
        ]);
    }

    /**
     * Show form to create/edit hero section
     */
    public function heroSectionForm($heroSection = null): View
    {
        if ($heroSection) {
            $heroSection = HeroSection::findOrFail($heroSection);
            $heroSection->load('media');
        } else {
            $heroSection = new HeroSection();
        }
        
        return view('dashboard.super-admin.hero-section-form', [
            'heroSection' => $heroSection,
        ]);
    }

    /**
     * Store or update hero section
     */
    public function storeHeroSection(Request $request, ?HeroSection $heroSection = null): RedirectResponse
    {
        $heroSectionId = ($heroSection && $heroSection->exists) ? $heroSection->id : null;
        
        $uniqueRule = $heroSectionId 
            ? 'required|string|max:255|unique:hero_sections,page,' . $heroSectionId
            : 'required|string|max:255|unique:hero_sections,page';
        
        $validated = $request->validate([
            'page' => $uniqueRule,
            'type' => 'required|string|in:image,video,slideshow',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'primary_button_text' => 'nullable|string|max:255',
            'primary_button_link' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($heroSection) {
            $heroSection->update($validated);
        } else {
            $heroSection = HeroSection::create($validated);
        }

        return redirect()
            ->route('super-admin.hero-section.edit', $heroSection)
            ->with('success', 'Hero section saved successfully!');
    }

    /**
     * Upload media for hero section
     */
    public function uploadHeroMedia(Request $request, HeroSection $heroSection): RedirectResponse
    {
        $request->validate([
            'media' => 'required|array',
            'media.*' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,webm|max:10240', // 10MB max
            'type' => 'required|string|in:image,video',
        ]);

        $uploadedMedia = [];

        foreach ($request->file('media') as $file) {
            $path = $this->storeImage($file, 'hero-media');
            
            if ($path) {
                $media = HeroMedia::create([
                    'hero_section_id' => $heroSection->id,
                    'type' => $request->input('type'),
                    'media_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'display_order' => $heroSection->media()->max('display_order') + 1,
                    'alt_text' => $file->getClientOriginalName(),
                ]);
                
                $uploadedMedia[] = $media;
            }
        }

        return redirect()
            ->route('super-admin.hero-section.edit', $heroSection)
            ->with('success', count($uploadedMedia) . ' media file(s) uploaded successfully!');
    }

    /**
     * Delete hero media
     */
    public function deleteHeroMedia(HeroMedia $media): RedirectResponse
    {
        $heroSection = $media->heroSection;
        
        // Delete file from storage
        $disk = $this->getStorageService()->getMediaDisk();
        if ($media->media_path && Storage::disk($disk)->exists($media->media_path)) {
            Storage::disk($disk)->delete($media->media_path);
        }
        
        $media->delete();

        return redirect()
            ->route('super-admin.hero-section.edit', $heroSection)
            ->with('success', 'Media deleted successfully!');
    }

    /**
     * Update hero media order
     */
    public function updateHeroMediaOrder(Request $request, HeroSection $heroSection): RedirectResponse
    {
        $request->validate([
            'media_order' => 'required|array',
            'media_order.*' => 'required|integer|exists:hero_media,id',
        ]);

        foreach ($request->input('media_order') as $order => $mediaId) {
            HeroMedia::where('id', $mediaId)
                ->where('hero_section_id', $heroSection->id)
                ->update(['display_order' => $order]);
        }

        return redirect()
            ->route('super-admin.hero-section.edit', $heroSection)
            ->with('success', 'Media order updated successfully!');
    }

    /**
     * Delete hero section
     */
    public function deleteHeroSection(HeroSection $heroSection): RedirectResponse
    {
        // Delete all media files
        foreach ($heroSection->media as $media) {
            if ($media->media_path && Storage::disk('public')->exists($media->media_path)) {
                Storage::disk('public')->delete($media->media_path);
            }
        }
        
        $heroSection->delete();

        return redirect()
            ->route('super-admin.hero-sections')
            ->with('success', 'Hero section deleted successfully!');
    }

    /**
     * Show branding management (logo and seal)
     */
    public function branding(): View
    {
        $logoContent = SiteContent::where('key', 'site_logo')->first();
        $sealContent = SiteContent::where('key', 'quality_seal')->first();
        
        return view('dashboard.super-admin.branding', [
            'logoContent' => $logoContent,
            'sealContent' => $sealContent,
        ]);
    }

    /**
     * Upload site logo
     */
    public function uploadLogo(Request $request): RedirectResponse
    {
        \Log::info('=== UPLOAD LOGO METHOD CALLED ===', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'has_file' => $request->hasFile('logo'),
            'all_input' => array_keys($request->all()),
            'files' => array_keys($request->allFiles()),
        ]);
        
        try {
            \Log::info('Upload logo started', [
                'has_file' => $request->hasFile('logo'),
                'file_size' => $request->hasFile('logo') ? $request->file('logo')->getSize() : 0,
                'content_type' => $request->header('Content-Type'),
                'content_length' => $request->header('Content-Length'),
            ]);

            $request->validate([
                'logo' => 'required|file|mimes:jpg,jpeg,png,webp,svg|max:20480', // 20MB max
            ]);

            $file = $request->file('logo');
            \Log::info('File validated', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);

            $path = $this->storeImage($file, 'branding');
            \Log::info('Image stored', ['path' => $path]);
            
            if ($path) {
                // Get URL - usar o disco configurado (MinIO ou public)
                $disk = $this->getStorageService()->getMediaDisk();
                \Log::info('Getting URL', ['disk' => $disk, 'path' => $path]);
                
                try {
                    // Para MinIO, tentar gerar URL assinada (temporary URL) ou URL pública
                    if ($disk === 'minio') {
                        try {
                            // Tentar gerar URL temporária assinada (válida por 1 hora)
                            $url = Storage::disk($disk)->temporaryUrl($path, now()->addHour());
                            \Log::info('MinIO temporary URL generated', ['url' => $url]);
                        } catch (\Exception $e1) {
                            // Se falhar, tentar URL pública
                            try {
                                $url = Storage::disk($disk)->url($path);
                                \Log::info('MinIO public URL generated', ['url' => $url]);
                                
                                // Se a URL usar winio, corrigir para ws3
                                if (str_contains($url, 'winio.conext.click')) {
                                    $url = str_replace('winio.conext.click', 'ws3.conext.click', $url);
                                    \Log::info('MinIO URL corrected to ws3', ['url' => $url]);
                                }
                            } catch (\Exception $e2) {
                                // Último recurso: construir URL manualmente
                                $minioUrl = config('filesystems.disks.minio.url', 'https://ws3.conext.click/poachurras');
                                $url = rtrim($minioUrl, '/') . '/' . $path;
                                \Log::warning('MinIO URL constructed manually', ['url' => $url, 'error' => $e2->getMessage()]);
                            }
                        }
                    } else {
                        $url = Storage::disk($disk)->url($path);
                        \Log::info('URL generated', ['url' => $url, 'disk' => $disk]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to get URL from configured disk', [
                        'error' => $e->getMessage(),
                        'disk' => $disk,
                        'path' => $path,
                    ]);
                    // Fallback: se for MinIO e falhar, tentar public
                    if ($disk === 'minio') {
                        try {
                            $url = Storage::disk('public')->url($path);
                            $disk = 'public';
                            \Log::warning('Using public disk as fallback', ['url' => $url]);
                        } catch (\Exception $e2) {
                            // Último fallback: construir URL manualmente
                            $url = '/storage/' . $path;
                            $disk = 'public';
                            \Log::warning('Using manual URL construction', ['url' => $url]);
                        }
                    } else {
                        // Para public, construir URL manualmente
                        $url = '/storage/' . $path;
                        \Log::info('Using manual URL construction for public disk', ['url' => $url]);
                    }
                }
                
                // Save to SiteContent
                $content = SiteContent::updateOrCreate(
                    ['key' => 'site_logo'],
                    [
                        'label' => 'Site Logo',
                        'content' => $url,
                        'type' => 'image',
                        'page' => 'global',
                        'section' => 'header',
                        'metadata' => [
                            'path' => $path,
                            'disk' => $disk,
                            'original_name' => $file->getClientOriginalName(),
                            'mime_type' => $file->getMimeType(),
                            'file_size' => $file->getSize(),
                        ],
                    ]
                );

                \Log::info('SiteContent saved', ['content_id' => $content->id, 'url' => $url]);

                return redirect()
                    ->route('super-admin.branding')
                    ->with('success', 'Logo enviado com sucesso! URL: ' . $url);
            }

            \Log::warning('Upload failed - no path returned');
            return redirect()
                ->route('super-admin.branding')
                ->with('error', 'Falha ao fazer upload da logo. Verifique os logs.');
        } catch (\Exception $e) {
            \Log::error('Upload logo error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('super-admin.branding')
                ->with('error', 'Erro ao fazer upload: ' . $e->getMessage());
        }
    }

    /**
     * Upload quality seal
     */
    public function uploadSeal(Request $request): RedirectResponse
    {
        $request->validate([
            'seal' => 'required|file|mimes:jpg,jpeg,png,webp,svg|max:5120', // 5MB max
        ]);

        $file = $request->file('seal');
        $path = $this->storeImage($file, 'branding');
        
        if ($path) {
            // Get URL - usar o disco configurado (MinIO ou public)
            $disk = $this->getStorageService()->getMediaDisk();
            \Log::info('Getting URL for seal', ['disk' => $disk, 'path' => $path]);
            
            try {
                $url = Storage::disk($disk)->url($path);
                \Log::info('Seal URL generated', ['url' => $url, 'disk' => $disk]);
                
                // Se a URL gerada usar winio.conext.click, substituir por ws3.conext.click
                if (str_contains($url, 'winio.conext.click')) {
                    $url = str_replace('winio.conext.click', 'ws3.conext.click', $url);
                    \Log::info('Seal URL corrected to use ws3.conext.click', ['url' => $url]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to get seal URL from configured disk', [
                    'error' => $e->getMessage(),
                    'disk' => $disk,
                    'path' => $path,
                ]);
                // Fallback: se for MinIO e falhar, tentar public
                if ($disk === 'minio') {
                    try {
                        $url = Storage::disk('public')->url($path);
                        $disk = 'public';
                        \Log::warning('Using public disk as fallback for seal', ['url' => $url]);
                    } catch (\Exception $e2) {
                        $url = '/storage/' . $path;
                        $disk = 'public';
                        \Log::warning('Using manual URL construction for seal', ['url' => $url]);
                    }
                } else {
                    $url = '/storage/' . $path;
                    \Log::info('Using manual URL construction for seal (public)', ['url' => $url]);
                }
            }
            
            // Save to SiteContent
            SiteContent::updateOrCreate(
                ['key' => 'quality_seal'],
                [
                    'label' => 'Quality Seal',
                    'content' => $url,
                    'type' => 'image',
                    'page' => 'home',
                    'section' => 'seal',
                    'metadata' => [
                        'path' => $path,
                        'disk' => $disk,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ],
                ]
            );

            // Also update config branding
            $filename = basename($path);
            config(['branding.quality_seal_logo_key' => $filename]);

            return redirect()
                ->route('super-admin.branding')
                ->with('success', 'Selo de qualidade enviado com sucesso!');
        }

        return redirect()
            ->route('super-admin.branding')
            ->with('error', 'Falha ao enviar selo.');
    }

    /**
     * Delete logo
     */
    public function deleteLogo(): RedirectResponse
    {
        try {
            $logoContent = SiteContent::where('key', 'site_logo')->first();
            
            if ($logoContent && isset($logoContent->metadata['path'])) {
                $path = $logoContent->metadata['path'];
                $disk = 'public'; // Sempre usar public disk
                
                \Log::info('Deleting logo', [
                    'path' => $path,
                    'disk' => $disk,
                ]);
                
                try {
                    // Tentar deletar do disco public
                    if (Storage::disk($disk)->exists($path)) {
                        Storage::disk($disk)->delete($path);
                        \Log::info('Logo file deleted', ['path' => $path]);
                    } else {
                        \Log::warning('Logo file not found on disk', [
                            'path' => $path,
                            'disk' => $disk,
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error deleting logo file', [
                        'error' => $e->getMessage(),
                        'path' => $path,
                        'disk' => $disk,
                    ]);
                    // Continuar mesmo se houver erro ao deletar o arquivo
                }
            }
            
            $logoContent?->delete();
            \Log::info('Logo content deleted from database');

            return redirect()
                ->route('super-admin.branding')
                ->with('success', 'Logo deletado com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Error in deleteLogo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()
                ->route('super-admin.branding')
                ->with('error', 'Erro ao deletar logo: ' . $e->getMessage());
        }
    }

    /**
     * Delete seal
     */
    public function deleteSeal(): RedirectResponse
    {
        try {
            $sealContent = SiteContent::where('key', 'quality_seal')->first();
            
            if ($sealContent && isset($sealContent->metadata['path'])) {
                $path = $sealContent->metadata['path'];
                $disk = 'public'; // Sempre usar public disk
                
                \Log::info('Deleting seal', [
                    'path' => $path,
                    'disk' => $disk,
                ]);
                
                try {
                    // Tentar deletar do disco public
                    if (Storage::disk($disk)->exists($path)) {
                        Storage::disk($disk)->delete($path);
                        \Log::info('Seal file deleted', ['path' => $path]);
                    } else {
                        \Log::warning('Seal file not found on disk', [
                            'path' => $path,
                            'disk' => $disk,
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error deleting seal file', [
                        'error' => $e->getMessage(),
                        'path' => $path,
                        'disk' => $disk,
                    ]);
                    // Continuar mesmo se houver erro ao deletar o arquivo
                }
            }
            
            $sealContent?->delete();
            \Log::info('Seal content deleted from database');

            return redirect()
                ->route('super-admin.branding')
                ->with('success', 'Selo deletado com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Error in deleteSeal', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()
                ->route('super-admin.branding')
                ->with('error', 'Erro ao deletar selo: ' . $e->getMessage());
        }
    }
}

