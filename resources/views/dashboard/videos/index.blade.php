@extends('layouts.app')

@section('title', 'Videos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Videos</h1>
            <p class="text-gray-600 mt-2">Curate multimedia storytelling that elevates your brand.</p>
        </div>
        <a href="{{ route('videos.create') }}" class="inline-flex items-center px-5 py-3 bg-churrasco-600 text-white rounded-xl shadow hover:bg-churrasco-700 transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Video
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl mb-8">
        <form method="GET" class="p-6 grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500"
                       placeholder="Title or description">
            </div>
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="category" name="category"
                        class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    <option value="">All</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>
                            {{ ucfirst(str_replace('_', ' ', $category)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="inline-flex items-center mt-8">
                    <input type="checkbox" name="featured" value="1" @checked(!empty($filters['featured']))
                           class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                    <span class="ml-2 text-sm text-gray-700">Featured only</span>
                </label>
            </div>
            <div class="flex items-end space-x-3">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="status" value="published" @checked(($filters['status'] ?? '') === 'published')
                           class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                    <span class="ml-2 text-sm text-gray-700">Published only</span>
                </label>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-xl hover:bg-gray-700 transition-colors duration-200">
                    Apply
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($videos as $video)
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                @if($video->thumbnail_url)
                    <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="w-full h-40 object-cover">
                @else
                    <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14m0-4v4m0-4L9 6m6 4L9 14m0 0l-4.553 2.276A1 1 0 013 15.382V8.618a1 1 0 011.447-.894L9 10"/>
                        </svg>
                    </div>
                @endif
                <div class="p-5 space-y-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $video->title }}</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $video->establishment->name ?? 'Shared content' }}</p>
                    </div>
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>{{ ucfirst(str_replace('_', ' ', $video->category)) }}</span>
                        <span>{{ $video->duration_seconds ? gmdate('i\m s\s', $video->duration_seconds) : '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>{{ $video->views_count ?? $video->view_count }} views</span>
                        <span>{{ $video->like_count }} likes</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $video->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $video->is_published ? 'Published' : 'Draft' }}
                        </span>
                        @if($video->is_featured)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                Featured
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <a href="{{ route('videos.show', $video) }}" class="text-sm text-churrasco-600 hover:text-churrasco-700 font-semibold">View</a>
                        <div class="inline-flex items-center space-x-2">
                            <a href="{{ route('videos.edit', $video) }}" class="text-sm text-gray-600 hover:text-gray-800 font-semibold">Edit</a>
                            <form action="{{ route('videos.destroy', $video) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this video?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-semibold">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center px-6 py-12 bg-white border border-dashed border-gray-300 rounded-2xl">
                <h2 class="text-lg font-semibold text-gray-900">No videos yet</h2>
                <p class="mt-2 text-sm text-gray-500">Start by publishing behind-the-scenes, tutorials or interviews.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $videos->links() }}
    </div>
</div>
@endsection












