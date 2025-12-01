@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', $product->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to list
            </a>
            <h1 class="mt-4 text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
            <p class="text-gray-600 mt-1">
                {{ ucfirst(str_replace('_', ' ', $product->category)) }} · {{ $product->establishment->name ?? 'Shared library' }}
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors duration-200">
                Edit
            </a>
            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
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
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Price</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $product->formatted_price }}</p>
                        @if($product->is_on_sale)
                            <p class="text-sm text-amber-600">Save {{ $product->discount_percentage }}%</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Inventory</p>
                        <p class="mt-1 text-lg font-medium text-gray-900">
                            {{ $product->stock_quantity }} units
                        </p>
                        <p class="text-sm text-gray-500">
                            Low stock threshold: {{ $product->low_stock_threshold }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Visibility</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $product->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($product->is_featured)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                    Featured
                                </span>
                            @endif
                            @if($product->is_digital)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                    Digital
                                </span>
                            @endif
                            @if($product->is_service)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                    Service
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($product->description)
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Description</h2>
                        <p class="mt-2 text-gray-700 leading-relaxed">{{ $product->description }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Ingredients</h3>
                        <ul class="mt-3 space-y-2 text-gray-700">
                            @forelse($product->ingredients ?? [] as $ingredient)
                                <li class="flex items-start">
                                    <span class="mt-1 w-2 h-2 rounded-full bg-churrasco-500 mr-3"></span>
                                    <span>{{ $ingredient }}</span>
                                </li>
                            @empty
                                <li class="text-sm text-gray-500">No ingredients provided.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Allergens</h3>
                        <ul class="mt-3 space-y-2 text-gray-700">
                            @forelse($product->allergens ?? [] as $allergen)
                                <li class="flex items-start">
                                    <span class="mt-1 w-2 h-2 rounded-full bg-red-500 mr-3"></span>
                                    <span>{{ $allergen }}</span>
                                </li>
                            @empty
                                <li class="text-sm text-gray-500">No allergen information.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                @if(!empty($product->nutritional_info))
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Nutritional information</h3>
                        <ul class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($product->nutritional_info as $info)
                                <li class="text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2">
                                    {{ $info }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Media</h2>
                @if(!empty($product->images))
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($product->images as $image)
                            <img src="{{ Storage::disk('public')->url($image) }}" alt="Product image" class="w-full h-36 object-cover rounded-xl border border-gray-200">
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">No images uploaded yet.</p>
                @endif

                @if(!empty($product->videos))
                    <div class="mt-6 space-y-3">
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Video links</h3>
                        @foreach($product->videos as $video)
                            <a href="{{ $video }}" target="_blank" class="block text-sm text-churrasco-600 hover:text-churrasco-700 truncate">
                                {{ $video }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Metadata</h2>
                <dl class="space-y-3 text-sm text-gray-700">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">SKU</dt>
                        <dd class="font-medium">{{ $product->sku ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Barcode</dt>
                        <dd class="font-medium">{{ $product->barcode ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Brand</dt>
                        <dd class="font-medium">{{ $product->brand ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Origin</dt>
                        <dd class="font-medium">{{ $product->origin ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Weight</dt>
                        <dd class="font-medium">{{ $product->weight ? $product->weight . ' kg' : '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Dimensions</dt>
                        <dd class="font-medium">
                            @if(!empty($product->dimensions))
                                {{ $product->dimensions['length'] ?? '—' }} x {{ $product->dimensions['width'] ?? '—' }} x {{ $product->dimensions['height'] ?? '—' }} cm
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Expiry date</dt>
                        <dd class="font-medium">{{ $product->expiry_date?->format('d/m/Y') ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Engagement</h2>
                <dl class="space-y-3 text-sm text-gray-700">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Views</dt>
                        <dd class="font-medium">{{ $product->view_count }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Purchases</dt>
                        <dd class="font-medium">{{ $product->purchase_count }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Rating</dt>
                        <dd class="font-medium">{{ number_format($product->rating ?? 0, 1) }} / 5 ({{ $product->review_count }} reviews)</dd>
                    </div>
                </dl>
            </div>

            @if(!empty($product->tags))
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Tags</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->tags as $tag)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($relatedPromotions->isNotEmpty())
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Related promotions</h2>
                    <ul class="space-y-4">
                        @foreach($relatedPromotions as $promotion)
                            <li class="flex items-start justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $promotion->title }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ ucfirst($promotion->promotion_type) }} · {{ $promotion->starts_at?->format('d/m') }} - {{ $promotion->ends_at?->format('d/m') ?? 'open' }}
                                    </p>
                                </div>
                                <a href="{{ route('promotions.show', $promotion) }}" class="text-sm text-churrasco-600 hover:text-churrasco-700 font-semibold">
                                    View
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection












