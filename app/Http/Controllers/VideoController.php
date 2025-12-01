<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithVideos;
use App\Http\Requests\Video\StoreVideoRequest;
use App\Http\Requests\Video\UpdateVideoRequest;
use App\Models\Video;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    use InteractsWithVideos;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Video::class);

        $query = Video::with('establishment')->where(function ($query) {
            $query->whereHas('establishment', fn ($q) => $q->where('user_id', auth()->id()))
                ->orWhereNull('establishment_id');
        });

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->string('status')->toString() === 'published');
        }

        if ($request->filled('sort')) {
            $sort = $request->string('sort')->toString();
            $direction = $request->string('direction', 'desc')->toString() === 'asc' ? 'asc' : 'desc';

            if (in_array($sort, ['view_count', 'like_count', 'duration_seconds'], true)) {
                $query->orderBy($sort, $direction);
            }
        } else {
            $query->latest();
        }

        $videos = $query->paginate(15)->withQueryString();

        return view('dashboard.videos.index', [
            'videos' => $videos,
            'categories' => Video::CATEGORIES,
            'filters' => $request->only(['search', 'category', 'status', 'featured', 'sort', 'direction']),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Video::class);

        $establishments = auth()->user()?->establishments()->pluck('name', 'id') ?? collect();

        return view('dashboard.videos.create', [
            'categories' => Video::CATEGORIES,
            'establishments' => $establishments,
        ]);
    }

    public function store(StoreVideoRequest $request): RedirectResponse
    {
        $this->authorize('create', Video::class);

        $data = $this->prepareVideoPayload($request->validated());
        $data['slug'] = $this->generateVideoSlug($data['title']);

        $video = Video::create($data);

        return redirect()
            ->route('videos.show', $video)
            ->with('success', 'Video created successfully.');
    }

    public function show(Video $video): View
    {
        $this->authorize('view', $video);

        $video->load('establishment');

        return view('dashboard.videos.show', [
            'video' => $video,
            'categories' => Video::CATEGORIES,
        ]);
    }

    public function edit(Video $video): View
    {
        $this->authorize('update', $video);

        $establishments = auth()->user()?->establishments()->pluck('name', 'id') ?? collect();

        return view('dashboard.videos.edit', [
            'video' => $video,
            'categories' => Video::CATEGORIES,
            'establishments' => $establishments,
        ]);
    }

    public function update(UpdateVideoRequest $request, Video $video): RedirectResponse
    {
        $data = $this->prepareVideoPayload($request->validated(), $video);

        if (isset($data['title']) && $data['title'] !== $video->title) {
            $data['slug'] = $this->generateVideoSlug($data['title'], $video->id);
        }

        $video->update($data);

        return redirect()
            ->route('videos.show', $video)
            ->with('success', 'Video updated successfully.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        $this->authorize('delete', $video);

        $video->delete();

        return redirect()
            ->route('videos.index')
            ->with('success', 'Video removed successfully.');
    }

}

