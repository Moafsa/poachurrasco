@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', $recipe->title)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('recipes.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to list
            </a>
            <h1 class="mt-4 text-3xl font-bold text-gray-900">{{ $recipe->title }}</h1>
            <p class="text-gray-600 mt-1">
                {{ ucfirst($recipe->category) }} · {{ ucfirst($recipe->difficulty) }} · {{ $recipe->servings }} servings
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('recipes.edit', $recipe) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors duration-200">
                Edit
            </a>
            <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this recipe?');">
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
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-6">
                @if(!empty($recipe->images))
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($recipe->images as $image)
                            <img src="{{ Storage::disk('public')->url($image) }}" alt="Recipe image" class="w-full h-40 object-cover rounded-xl border border-gray-200">
                        @endforeach
                    </div>
                @endif

                @if($recipe->summary)
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Summary</h2>
                        <p class="mt-2 text-gray-700 leading-relaxed">{{ $recipe->summary }}</p>
                    </div>
                @endif

                @if($recipe->description)
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Description</h2>
                        <p class="mt-2 text-gray-700 leading-relaxed">{{ $recipe->description }}</p>
                    </div>
                @endif

            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Preparation</p>
                        <p class="mt-1 text-lg font-medium text-gray-900">{{ $recipe->prep_time_minutes ?? '—' }} min</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Cooking</p>
                        <p class="mt-1 text-lg font-medium text-gray-900">{{ $recipe->cook_time_minutes ?? '—' }} min</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Resting</p>
                        <p class="mt-1 text-lg font-medium text-gray-900">{{ $recipe->rest_time_minutes ?? '—' }} min</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Ingredients</h3>
                        <ul class="mt-3 space-y-2 text-gray-700">
                            @foreach($recipe->ingredients ?? [] as $ingredient)
                                <li class="flex items-start">
                                    <span class="mt-1 w-2 h-2 rounded-full bg-churrasco-500 mr-3"></span>
                                    <span>{{ $ingredient }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Tips</h3>
                        <ul class="mt-3 space-y-2 text-gray-700">
                            @forelse($recipe->tips ?? [] as $tip)
                                <li class="flex items-start">
                                    <span class="mt-1 w-2 h-2 rounded-full bg-amber-500 mr-3"></span>
                                    <span>{{ $tip }}</span>
                                </li>
                            @empty
                                <li class="text-sm text-gray-500">No additional tips.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Instructions</h3>
                    <ol class="mt-3 space-y-3 text-gray-700 list-decimal list-inside">
                        @foreach($recipe->instructions ?? [] as $step)
                            <li>{{ $step }}</li>
                        @endforeach
                    </ol>
                </div>

                @if($recipe->video_url)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Video</h3>
                        <a href="{{ $recipe->video_url }}" target="_blank" class="mt-2 inline-flex items-center text-churrasco-600 hover:text-churrasco-700 font-semibold">
                            Watch tutorial
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Status</h2>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $recipe->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $recipe->is_published ? 'Published' : 'Draft' }}
                    </span>
                    @if($recipe->is_featured)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                            Featured
                        </span>
                    @endif
                </div>
                <div class="text-sm text-gray-600">
                    Published at: {{ $recipe->published_at?->format('d/m/Y H:i') ?? 'Not scheduled' }}
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-3 text-sm text-gray-700">
                <div class="flex justify-between">
                    <span class="text-gray-500">Views</span>
                    <span class="font-medium">{{ $recipe->view_count }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Favorites</span>
                    <span class="font-medium">{{ $recipe->favorite_count }}</span>
                </div>
            </div>

            @if(!empty($recipe->nutrition_facts))
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Nutrition facts</h2>
                    <pre class="text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-xl p-4 overflow-auto">{{ json_encode($recipe->nutrition_facts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-3 text-sm text-gray-700">
                <div class="flex justify-between">
                    <span class="text-gray-500">Created</span>
                    <span class="font-medium">{{ $recipe->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Last update</span>
                    <span class="font-medium">{{ $recipe->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection












