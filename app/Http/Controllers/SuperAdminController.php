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
    public function heroSectionForm(HeroSection $heroSection = null): View
    {
        $heroSection = $heroSection ?? new HeroSection();
        
        if ($heroSection->exists) {
            $heroSection->load('media');
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
        $validated = $request->validate([
            'page' => 'required|string|max:255|unique:hero_sections,page,' . ($heroSection?->id ?? ''),
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
        if ($media->media_path && Storage::disk('public')->exists($media->media_path)) {
            Storage::disk('public')->delete($media->media_path);
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
}

