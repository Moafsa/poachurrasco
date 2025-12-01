@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', $promotion->title)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('promotions.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to list
            </a>
            <h1 class="mt-4 text-3xl font-bold text-gray-900">{{ $promotion->title }}</h1>
            <p class="text-gray-600 mt-1">{{ $promotion->establishment->name ?? 'Shared promotion' }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('promotions.edit', $promotion) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors duration-200">
                Edit
            </a>
            <form action="{{ route('promotions.destroy', $promotion) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this promotion?');">
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
                @if($promotion->banner_image)
                    <img src="{{ Storage::disk('public')->url($promotion->banner_image) }}" alt="{{ $promotion->title }}" class="w-full h-56 object-cover rounded-xl border border-gray-200">
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Promotion type</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ ucfirst($promotion->promotion_type) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Discount</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900">
                            {{ $promotion->promotion_type === 'percentage' ? $promotion->discount_value . '%' : 'R$ ' . number_format($promotion->discount_value, 2, ',', '.') }}
                        </p>
                        @if($promotion->minimum_order_value)
                            <p class="text-sm text-gray-500 mt-1">
                                Min. order R$ {{ number_format($promotion->minimum_order_value, 2, ',', '.') }}
                            </p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Promo code</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $promotion->promo_code ?? 'Not required' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Schedule</p>
                        <p class="mt-1 text-gray-900 font-medium">
                            {{ $promotion->starts_at?->format('d/m/Y H:i') ?? 'Immediate start' }} —
                            {{ $promotion->ends_at?->format('d/m/Y H:i') ?? 'Open-ended' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Usage</p>
                        <p class="mt-1 text-gray-900 font-medium">
                            {{ $promotion->usage_count }} / {{ $promotion->usage_limit ?? 'Unlimited' }}
                        </p>
                    </div>
                </div>

                @if($promotion->description)
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Description</h2>
                        <p class="mt-2 text-gray-700 leading-relaxed">{{ $promotion->description }}</p>
                    </div>
                @endif

                @if(!empty($promotion->terms))
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Terms & Conditions</h2>
                        <p class="mt-2 text-gray-700 leading-relaxed whitespace-pre-line">{{ $promotion->terms }}</p>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900">Applicable products</h2>
                @if(!empty($promotion->applicable_products))
                    <ul class="mt-4 space-y-3">
                        @foreach($promotion->applicable_products as $productId)
                            @php
                                $product = $applicableProducts->get($productId);
                            @endphp
                            <li class="flex items-center justify-between border border-gray-200 rounded-xl px-4 py-3">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $product->name ?? "Product #{$productId}" }}</p>
                                    <p class="text-sm text-gray-500">{{ $product?->formatted_price ?? '—' }}</p>
                                </div>
                                <a href="{{ $product ? route('products.show', $product) : '#' }}" class="text-sm text-churrasco-600 hover:text-churrasco-700 font-semibold" @if(!$product) aria-disabled="true" @endif>
                                    View
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="mt-3 text-sm text-gray-500">Applies to all products.</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Status</h2>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                        @class([
                            'bg-emerald-100 text-emerald-700' => $promotion->status === 'active',
                            'bg-blue-100 text-blue-700' => $promotion->status === 'scheduled',
                            'bg-amber-100 text-amber-700' => $promotion->status === 'paused',
                            'bg-gray-100 text-gray-600' => in_array($promotion->status, ['draft', 'expired']),
                        ])">
                        {{ ucfirst($promotion->status) }}
                    </span>
                    @if($promotion->is_featured)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600">
                            Featured
                        </span>
                    @endif
                    @if($promotion->is_stackable)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                            Stackable
                        </span>
                    @endif
                </div>
            </div>

            @if(!empty($promotion->channels))
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Channels</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($promotion->channels as $channel)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                {{ $channel }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-3 text-sm text-gray-700">
                <div class="flex justify-between">
                    <span class="text-gray-500">Created</span>
                    <span class="font-medium">{{ $promotion->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Last update</span>
                    <span class="font-medium">{{ $promotion->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

