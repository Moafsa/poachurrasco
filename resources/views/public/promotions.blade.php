@extends('layouts.app')

@section('title', 'Promotions')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-red-50">
    <div class="mx-auto max-w-7xl px-4 py-16 space-y-12">
        <header class="text-center">
            <h1 class="text-4xl font-black text-gray-900 sm:text-5xl">Barbecue Promotions</h1>
            <p class="mt-4 text-lg text-gray-600">
                Discover limited-time discounts and special offers curated by establishments across Porto Alegre.
            </p>
        </header>

        <section class="bg-white rounded-2xl shadow-sm border border-orange-100 p-6">
            <form method="GET" class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold uppercase tracking-wide text-orange-600">Filters:</span>
                    <a href="{{ route('promotions') }}" class="{{ request()->hasAny(['type', 'featured']) ? 'text-gray-500 hover:text-gray-700' : 'text-orange-600 font-semibold' }}">
                        All
                    </a>
                    <a href="{{ route('promotions', array_filter(['type' => 'percentage', 'featured' => request('featured')])) }}" class="{{ request('type') === 'percentage' ? 'text-orange-600 font-semibold' : 'text-gray-500 hover:text-gray-700' }}">
                        Percentage
                    </a>
                    <a href="{{ route('promotions', array_filter(['type' => 'fixed', 'featured' => request('featured')])) }}" class="{{ request('type') === 'fixed' ? 'text-orange-600 font-semibold' : 'text-gray-500 hover:text-gray-700' }}">
                        Fixed amount
                    </a>
                </div>
                <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="featured" value="1" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                           onchange="this.form.submit()" {{ request()->boolean('featured') ? 'checked' : '' }}>
                    Featured only
                </label>
            </form>
        </section>

        <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($promotions as $promotion)
                <article class="flex h-full flex-col rounded-2xl border border-orange-100 bg-white/80 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wide text-orange-600">
                        <span>{{ strtoupper($promotion->promotion_type) }}</span>
                        @if ($promotion->is_featured)
                            <span class="rounded-full bg-orange-500 px-2 py-0.5 text-white">Featured</span>
                        @endif
                    </div>
                    <h2 class="mt-4 text-2xl font-semibold text-gray-900">{{ $promotion->title }}</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ \Illuminate\Support\Str::limit($promotion->description ?? 'Exclusive promotion available for a limited time.', 140) }}
                    </p>
                    <dl class="mt-4 space-y-2 text-sm text-gray-700">
                        <div class="flex items-center justify-between">
                            <span>Discount</span>
                            <span class="font-semibold text-gray-900">
                                @if ($promotion->promotion_type === 'percentage')
                                    {{ number_format($promotion->discount_value, 0) }}%
                                @else
                                    R$ {{ number_format($promotion->discount_value, 2, ',', '.') }}
                                @endif
                            </span>
                        </div>
                        @if ($promotion->minimum_order_value)
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>Minimum order</span>
                                <span>R$ {{ number_format($promotion->minimum_order_value, 2, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Valid</span>
                            <span>{{ optional($promotion->starts_at)->format('d/m') ?? 'Now' }} - {{ optional($promotion->ends_at)->format('d/m') ?? 'Open' }}</span>
                        </div>
                    </dl>
                    @if ($promotion->establishment)
                        <div class="mt-6 text-sm">
                            <span class="text-gray-500">Offered by</span>
                            <a href="{{ route('public.establishment', $promotion->establishment) }}" class="ml-2 font-semibold text-orange-600 hover:underline">
                                {{ $promotion->establishment->name }}
                            </a>
                        </div>
                    @endif
                </article>
            @empty
                <div class="md:col-span-2 xl:col-span-3 rounded-2xl border border-dashed border-orange-200 bg-white p-12 text-center text-gray-500">
                    No promotions available at the moment. Check back soon or follow our partner establishments for updates.
                </div>
            @endforelse
        </section>

        <div>
            {{ $promotions->links() }}
        </div>
    </div>
</div>
@endsection












