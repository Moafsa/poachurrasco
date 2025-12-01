@php
    $tagsValue = is_array(old('tags')) ? implode(', ', old('tags')) : (isset($video) ? implode(', ', $video->tags ?? []) : '');
    $captions = collect(old('captions', $video->captions ?? []))->values();
@endphp

<form action="{{ $action }}" method="POST" class="space-y-8">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $video->title ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500" required>
            </div>
            <div>
                <label for="establishment_id" class="block text-sm font-medium text-gray-700">Establishment (optional)</label>
                <select id="establishment_id" name="establishment_id"
                        class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    <option value="">Shared content</option>
                    @foreach($establishments as $establishmentId => $establishmentName)
                        <option value="{{ $establishmentId }}" @selected((int) old('establishment_id', $video->establishment_id ?? '') === (int) $establishmentId)>
                            {{ $establishmentName }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="category" name="category"
                        class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500" required>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" @selected(old('category', $video->category ?? '') === $category)>
                            {{ ucfirst(str_replace('_', ' ', $category)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="provider" class="block text-sm font-medium text-gray-700">Provider</label>
                <input type="text" id="provider" name="provider" value="{{ old('provider', $video->provider ?? 'youtube') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div>
                <label for="provider_video_id" class="block text-sm font-medium text-gray-700">Provider video ID</label>
                <input type="text" id="provider_video_id" name="provider_video_id" value="{{ old('provider_video_id', $video->provider_video_id ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label for="video_url" class="block text-sm font-medium text-gray-700">Video URL</label>
                <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $video->video_url ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500" required>
            </div>
            <div>
                <label for="thumbnail_url" class="block text-sm font-medium text-gray-700">Thumbnail URL</label>
                <input type="url" id="thumbnail_url" name="thumbnail_url" value="{{ old('thumbnail_url', $video->thumbnail_url ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500" placeholder="https://">
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div>
                <label for="duration_seconds" class="block text-sm font-medium text-gray-700">Duration (seconds)</label>
                <input type="number" id="duration_seconds" name="duration_seconds" value="{{ old('duration_seconds', $video->duration_seconds ?? '') }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div>
                <label class="inline-flex items-center mt-8">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $video->is_featured ?? false))
                           class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                    <span class="ml-2 text-sm text-gray-700">Feature this video</span>
                </label>
            </div>
            <div>
                <label class="inline-flex items-center mt-8">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $video->is_published ?? false))
                           class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                    <span class="ml-2 text-sm text-gray-700">Publish immediately</span>
                </label>
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" rows="4"
                      class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">{{ old('description', $video->description ?? '') }}</textarea>
        </div>

        <div>
            <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
            <input type="text" id="tags" name="tags" value="{{ $tagsValue }}" placeholder="separate values with comma"
                   class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label for="published_at" class="block text-sm font-medium text-gray-700">Publish at</label>
                <input type="datetime-local" id="published_at" name="published_at"
                       value="{{ old('published_at', optional($video->published_at ?? null)?->format('Y-m-d\TH:i')) }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="view_count" class="block text-sm font-medium text-gray-700">Views</label>
                    <input type="number" id="view_count" name="view_count" value="{{ old('view_count', $video->view_count ?? 0) }}"
                           class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                </div>
                <div>
                    <label for="like_count" class="block text-sm font-medium text-gray-700">Likes</label>
                    <input type="number" id="like_count" name="like_count" value="{{ old('like_count', $video->like_count ?? 0) }}"
                           class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                </div>
                <div>
                    <label for="share_count" class="block text-sm font-medium text-gray-700">Shares</label>
                    <input type="number" id="share_count" name="share_count" value="{{ old('share_count', $video->share_count ?? 0) }}"
                           class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-4">
        <h2 class="text-lg font-semibold text-gray-900">Captions</h2>
        <p class="text-sm text-gray-500">Provide language code (ISO 639-1) and the caption URL. Leave blank if not required.</p>
        <div class="space-y-4">
            @for($index = 0; $index < 3; $index++)
                @php
                    $caption = $captions[$index] ?? ['language' => '', 'url' => ''];
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border border-gray-200 rounded-xl p-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Language</label>
                        <input type="text" name="captions[{{ $index }}][language]" value="{{ $caption['language'] ?? '' }}"
                               placeholder="en"
                               class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Caption URL</label>
                        <input type="url" name="captions[{{ $index }}][url]" value="{{ $caption['url'] ?? '' }}"
                               placeholder="https://..."
                               class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <div class="flex items-center justify-between">
        <a href="{{ route('videos.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors duration-200">
            Cancel
        </a>
        <button type="submit" class="inline-flex items-center px-6 py-3 bg-churrasco-600 text-white rounded-xl hover:bg-churrasco-700 transition-colors duration-200">
            {{ $submitLabel }}
        </button>
    </div>
</form>












