@extends('layouts.app')

@section('title', 'Hero Sections Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900">Hero Sections Management</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Manage hero images, videos, and slideshows for pages</p>
            </div>
            <a href="{{ route('super-admin.hero-section.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create Hero Section
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($heroSections as $hero)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                    <div class="relative h-48 bg-gray-100">
                        @if($hero->primary_image)
                            <img src="{{ Storage::disk('public')->url($hero->primary_image) }}" alt="{{ $hero->page }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        @if($hero->is_active)
                            <span class="absolute top-4 right-4 px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded">Active</span>
                        @else
                            <span class="absolute top-4 right-4 px-2 py-1 bg-gray-500 text-white text-xs font-semibold rounded">Inactive</span>
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ ucfirst($hero->page) }}</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold mr-2">{{ ucfirst($hero->type) }}</span>
                            {{ $hero->media->count() }} media file(s)
                        </p>
                        @if($hero->title)
                            <p class="text-sm text-gray-700 mb-4 font-medium">{{ Str::limit($hero->title, 50) }}</p>
                        @endif
                        <div class="flex gap-2">
                            <a href="{{ route('super-admin.hero-section.edit', $hero) }}" class="flex-1 text-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('super-admin.hero-section.delete', $hero) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this hero section?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-2xl shadow-lg">
                    <p class="text-gray-500 mb-4">No hero sections created yet</p>
                    <a href="{{ route('super-admin.hero-section.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Create First Hero Section
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection



