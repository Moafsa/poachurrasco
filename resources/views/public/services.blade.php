@extends('layouts.app')

@section('title', 'Services')

@section('content')
@php
    use Illuminate\Support\Str;
@endphp
<div class="min-h-screen bg-gray-50">
    <div class="mx-auto max-w-7xl px-4 py-16 space-y-12">
        <header class="text-center">
            <h1 class="text-4xl font-black text-gray-900 sm:text-5xl">Signature Barbecue Services</h1>
            <p class="mt-4 text-lg text-gray-600">
                Reserve bespoke barbecue experiences, catering, consulting, and on-site events powered by our partner establishments.
            </p>
        </header>

        <section class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <form method="GET" class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-semibold uppercase tracking-wide text-orange-600">Category:</span>
                    <a href="{{ route('services.public') }}"
                       class="rounded-lg px-3 py-1 text-sm font-semibold transition {{ empty($selectedCategory) ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        All
                    </a>
                    @foreach ($categories as $category)
                        <a href="{{ route('services.public', array_filter(['category' => $category, 'featured' => request('featured')])) }}"
                           class="rounded-lg px-3 py-1 text-sm font-semibold transition {{ $selectedCategory === $category ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            {{ ucfirst(str_replace('_', ' ', $category)) }}
                        </a>
                    @endforeach
                </div>
                <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="featured" value="1" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                           onchange="this.form.submit()" {{ request()->boolean('featured') ? 'checked' : '' }}>
                    Featured only
                </label>
            </form>
        </section>

        <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($services as $service)
                <article class="flex h-full flex-col rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow">
                    <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wide text-orange-600">
                        <span>{{ ucfirst(str_replace('_', ' ', $service->category)) }}</span>
                        @if ($service->is_featured)
                            <span class="rounded-full bg-orange-500 px-2 py-0.5 text-white">Featured</span>
                        @endif
                    </div>
                    <h2 class="mt-4 text-2xl font-semibold text-gray-900">{{ $service->name }}</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ Str::limit($service->description ?? 'Complete barbecue service with curated cuts, staff, and equipment.', 140) }}
                    </p>
                    <dl class="mt-4 space-y-2 text-sm text-gray-700">
                        <div class="flex items-center justify-between">
                            <span>Price</span>
                            <span class="font-semibold text-gray-900">R$ {{ number_format($service->price, 2, ',', '.') }}</span>
                        </div>
                        @if ($service->setup_fee)
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>Setup fee</span>
                                <span>R$ {{ number_format($service->setup_fee, 2, ',', '.') }}</span>
                            </div>
                        @endif
                        @if ($service->capacity)
                            <div class="flex items-center justify-between">
                                <span>Capacity</span>
                                <span>{{ $service->capacity }} guests</span>
                            </div>
                        @endif
                        @if ($service->duration_minutes)
                            <div class="flex items-center justify-between">
                                <span>Duration</span>
                                <span>{{ $service->duration_minutes }} minutes</span>
                            </div>
                        @endif
                    </dl>
                    <div class="mt-6 text-sm text-gray-600">
                        <span class="text-gray-500">Includes:</span>
                        <span class="ml-2">
                            {{ $service->includes_meat ? 'Meat • ' : '' }}
                            {{ $service->includes_staff ? 'Staff • ' : '' }}
                            {{ $service->includes_equipment ? 'Equipment' : '' }}
                        </span>
                    </div>
                    @if ($service->establishment)
                        <div class="mt-6 text-sm">
                            <span class="text-gray-500">Provided by</span>
                            <a href="{{ route('public.establishment', $service->establishment) }}" class="ml-2 font-semibold text-orange-600 hover:underline">
                                {{ $service->establishment->name }}
                            </a>
                        </div>
                    @endif
                </article>
            @empty
                <div class="md:col-span-2 xl:col-span-3 rounded-2xl border border-dashed border-gray-200 bg-white p-12 text-center text-gray-500">
                    No services available at the moment. Publish services in the dashboard to populate this catalog automatically.
                </div>
            @endforelse
        </section>

        <div>
            {{ $services->links() }}
        </div>
    </div>
</div>
@endsection












