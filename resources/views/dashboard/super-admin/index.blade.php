@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-gray-900">Super Admin Dashboard</h1>
                    <p class="text-sm sm:text-base text-gray-600 mt-1 sm:mt-2">Manage site content and hero sections</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Hero Sections Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Hero Sections</h2>
                        <p class="text-sm text-gray-600 mt-1">Manage hero images, videos, and slideshows</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-900">{{ $heroSections->count() }}</span>
                    <a href="{{ route('super-admin.hero-sections') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Manage
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Site Content Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Site Content</h2>
                        <p class="text-sm text-gray-600 mt-1">Edit texts and content across the site</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-900">{{ $siteContents->count() }}</span>
                    <a href="{{ route('super-admin.content') }}" class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
                        Manage
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Hero Sections -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Recent Hero Sections</h2>
                <a href="{{ route('super-admin.hero-sections') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                    View all
                </a>
            </div>
            @if($heroSections->count() > 0)
                <div class="space-y-4">
                    @foreach($heroSections->take(5) as $hero)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                    @if($hero->primary_image)
                                        <img src="{{ Storage::disk('public')->url($hero->primary_image) }}" alt="{{ $hero->page }}" class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ ucfirst($hero->page) }}</h3>
                                    <p class="text-sm text-gray-600">{{ $hero->type }} • {{ $hero->media->count() }} media file(s)</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($hero->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">Active</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded">Inactive</span>
                                @endif
                                <a href="{{ route('super-admin.hero-section.edit', $hero) }}" class="text-blue-600 hover:text-blue-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 mb-4">No hero sections created yet</p>
                    <a href="{{ route('super-admin.hero-section.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Create First Hero Section
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection






