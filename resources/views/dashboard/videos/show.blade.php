@extends('layouts.app')

@section('title', $video->title)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('videos.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to list
            </a>
            <h1 class="mt-4 text-3xl font-bold text-gray-900">{{ $video->title }}</h1>
            <p class="text-gray-600 mt-1">{{ ucfirst(str_replace('_', ' ', $video->category)) }} · {{ $video->establishment->name ?? 'Shared content' }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('videos.edit', $video) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors duration-200">
                Edit
            </a>
            <form action="{{ route('videos.destroy', $video) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-colors duration-200">
                    Delete
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                @if($video->thumbnail_url)
                    <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="w-full h-72 object-cover">
                @else
                    <div class="w-full h-72 bg-gray-100 flex items-center justify-center text-gray-400">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14m0-4v4m0-4L9 6m6 4L9 14m0 0l-4.553 2.276A1 1 0 013 15.382V8.618a1 1 0 011.447-.894L9 10"/>
                        </svg>
                    </div>
                @endif
                <div class="p-6 space-y-4">
                    <p class="text-sm text-gray-500">Provider</p>
                    <p class="text-lg font-medium text-gray-900">{{ ucfirst($video->provider) }}</p>
                    <a href="{{ $video->video_url }}" target="_blank" class="inline-flex items-center text-churrasco-600 hover:text-churrasco-700 font-semibold">
                        Open video
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>

            @if($video->description)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900">Description</h2>
                    <p class="mt-2 text-gray-700 leading-relaxed">{{ $video->description }}</p>
                </div>
            @endif

            @if(!empty($video->tags))
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Tags</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($video->tags as $tag)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($video->captions))
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Caption files</h2>
                    <ul class="space-y-3 text-sm text-gray-700">
                        @foreach($video->captions as $caption)
                            <li class="flex items-center justify-between border border-gray-200 rounded-xl px-4 py-3">
                                <span class="font-medium">{{ strtoupper($caption['language'] ?? '—') }}</span>
                                <a href="{{ $caption['url'] ?? '#' }}" target="_blank" class="text-churrasco-600 hover:text-churrasco-700 font-semibold">
                                    Download
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Status</h2>
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
                <div class="text-sm text-gray-600">
                    Published at: {{ $video->published_at?->format('d/m/Y H:i') ?? 'Not scheduled' }}
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-3 text-sm text-gray-700">
                <div class="flex justify-between">
                    <span class="text-gray-500">Duration</span>
                    <span class="font-medium">{{ $video->duration_seconds ? gmdate('i\m s\s', $video->duration_seconds) : '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Views</span>
                    <span class="font-medium">{{ $video->view_count }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Likes</span>
                    <span class="font-medium">{{ $video->like_count }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Shares</span>
                    <span class="font-medium">{{ $video->share_count }}</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-3 text-sm text-gray-700">
                <div class="flex justify-between">
                    <span class="text-gray-500">Created</span>
                    <span class="font-medium">{{ $video->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Last update</span>
                    <span class="font-medium">{{ $video->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection












