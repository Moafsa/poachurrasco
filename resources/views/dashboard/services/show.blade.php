@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', $service->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('services.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to list
            </a>
            <h1 class="mt-4 text-3xl font-bold text-gray-900">{{ $service->name }}</h1>
            <p class="text-gray-600 mt-1">{{ ucfirst(str_replace('_', ' ', $service->category)) }} · {{ $service->establishment->name ?? 'Shared service' }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('services.edit', $service) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors duration-200">
                Edit
            </a>
            <form action="{{ route('services.destroy', $service) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');">
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
                @if(!empty($service->images))
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($service->images as $image)
                            <img src="{{ Storage::disk('public')->url($image) }}" alt="Service image" class="w-full h-40 object-cover rounded-xl border border-gray-200">
                        @endforeach
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Price</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">R$ {{ number_format($service->price, 2, ',', '.') }}</p>
                        @if($service->setup_fee)
                            <p class="text-sm text-gray-500 mt-1">Setup fee: R$ {{ number_format($service->setup_fee, 2, ',', '.') }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Duration</p>
                        <p class="mt-1 text-lg font-medium text-gray-900">
                            {{ $service->duration_minutes ? $service->duration_minutes . ' minutes' : 'Flexible' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Capacity</p>
                        <p class="mt-1 text-lg font-medium text-gray-900">
                            {{ $service->capacity ? $service->capacity . ' guests' : 'Custom size' }}
                        </p>
                    </div>
                </div>

                @if($service->description)
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Description</h2>
                        <p class="mt-2 text-gray-700 leading-relaxed">{{ $service->description }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full {{ $service->includes_meat ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                        <span class="text-sm text-gray-700">Includes meat</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full {{ $service->includes_staff ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                        <span class="text-sm text-gray-700">Includes staff</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full {{ $service->includes_equipment ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                        <span class="text-sm text-gray-700">Includes equipment</span>
                    </div>
                </div>
            </div>

            @if(!empty($service->addons))
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Add-ons</h2>
                    <ul class="space-y-3">
                        @foreach($service->addons as $addon)
                            <li class="flex items-center justify-between border border-gray-200 rounded-xl px-4 py-3">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $addon['name'] ?? 'Unnamed add-on' }}</p>
                                    @if(!empty($addon['description']))
                                        <p class="text-sm text-gray-500">{{ $addon['description'] }}</p>
                                    @endif
                                </div>
                                @if(isset($addon['price']))
                                    <span class="text-sm font-semibold text-gray-900">
                                        R$ {{ number_format($addon['price'], 2, ',', '.') }}
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(!empty($service->service_hours))
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Service hours</h2>
                    <ul class="space-y-3 text-sm text-gray-700">
                        @foreach($service->service_hours as $slot)
                            <li class="flex items-center justify-between border border-gray-200 rounded-xl px-4 py-3">
                                <span class="font-medium text-gray-900">{{ $slot['day'] ?? 'Day not defined' }}</span>
                                <span>{{ ($slot['start'] ?? '—') }} - {{ ($slot['end'] ?? '—') }}</span>
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
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $service->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    @if($service->is_featured)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                            Featured
                        </span>
                    @endif
                </div>
            </div>

            @if(!empty($service->tags))
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Tags</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($service->tags as $tag)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-3 text-sm text-gray-700">
                <div class="flex justify-between">
                    <span class="text-gray-500">Views</span>
                    <span class="font-medium">{{ $service->view_count }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Rating</span>
                    <span class="font-medium">
                        {{ number_format($service->rating ?? 0, 1) }} / 5 ({{ $service->review_count }} reviews)
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Created</span>
                    <span class="font-medium">{{ $service->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Last update</span>
                    <span class="font-medium">{{ $service->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection












