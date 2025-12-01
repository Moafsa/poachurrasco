<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\ApiResponses;
use App\Http\Controllers\Concerns\InteractsWithVideos;
use App\Http\Controllers\Controller;
use App\Http\Requests\Video\StoreVideoRequest;
use App\Http\Requests\Video\UpdateVideoRequest;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    use ApiResponses;
    use InteractsWithVideos;

    public function index(Request $request): JsonResponse
    {
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

        $videos = $query->paginate($request->integer('per_page', 15))->withQueryString();

        return $this->paginatedResponse($videos);
    }

    public function store(StoreVideoRequest $request): JsonResponse
    {
        $data = $this->prepareVideoPayload($request->validated());
        $data['slug'] = $this->generateVideoSlug($data['title']);

        $video = Video::create($data);

        return $this->createdResponse($video->load('establishment'));
    }

    public function show(Video $video): JsonResponse
    {
        $this->authorize('view', $video);

        return $this->successResponse($video->load('establishment'));
    }

    public function update(UpdateVideoRequest $request, Video $video): JsonResponse
    {
        $data = $this->prepareVideoPayload($request->validated(), $video);

        if (isset($data['title']) && $data['title'] !== $video->title) {
            $data['slug'] = $this->generateVideoSlug($data['title'], $video->id);
        }

        $video->update($data);

        return $this->successResponse($video->fresh('establishment'));
    }

    public function destroy(Video $video): JsonResponse
    {
        $this->authorize('delete', $video);
        $video->delete();

        return $this->deletedResponse();
    }
}












