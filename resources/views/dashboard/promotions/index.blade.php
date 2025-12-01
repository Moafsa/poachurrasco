@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Promotions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Promotions</h1>
            <p class="text-gray-600 mt-2">Boost conversions with targeted incentives and discounts.</p>
        </div>
        <a href="{{ route('promotions.create') }}" class="inline-flex items-center px-5 py-3 bg-churrasco-600 text-white rounded-xl shadow hover:bg-churrasco-700 transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Promotion
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl mb-8">
        <form method="GET" class="p-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500"
                       placeholder="Title or promo code">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status"
                        class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    <option value="">All</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="promotion_type" class="block text-sm font-medium text-gray-700">Type</label>
                <select id="promotion_type" name="promotion_type"
                        class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    <option value="">All</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" @selected(($filters['promotion_type'] ?? '') === $type)>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <label class="inline-flex items-center mr-4">
                    <input type="checkbox" name="featured" value="1" @checked(!empty($filters['featured']))
                           class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                    <span class="ml-2 text-sm text-gray-700">Featured only</span>
                </label>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-xl hover:bg-gray-700 transition-colors duration-200">
                    Apply
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Promotion</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Window</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Usage</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($promotions as $promotion)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-4">
                                @if($promotion->banner_image)
                                    <img src="{{ Storage::disk('public')->url($promotion->banner_image) }}" alt="{{ $promotion->title }}" class="w-12 h-12 rounded-xl object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m2 14H7a2 2 0 01-2-2V8a2 2 0 012-2h3l2-2h2l2 2h3a2 2 0 012 2v12a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $promotion->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $promotion->establishment->name ?? 'Shared promotion' }}</div>
                                    @if($promotion->promo_code)
                                        <div class="mt-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                            Code: {{ $promotion->promo_code }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $promotion->starts_at?->format('d M, H:i') ?? 'Immediate' }} —
                            {{ $promotion->ends_at?->format('d M, H:i') ?? 'Open-ended' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ ucfirst($promotion->promotion_type) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
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
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-gray-600">
                            {{ $promotion->usage_count }} / {{ $promotion->usage_limit ?? '∞' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center space-x-2">
                                <a href="{{ route('promotions.show', $promotion) }}" class="text-sm text-churrasco-600 hover:text-churrasco-700 font-semibold">View</a>
                                <a href="{{ route('promotions.edit', $promotion) }}" class="text-sm text-gray-600 hover:text-gray-800 font-semibold">Edit</a>
                                <form action="{{ route('promotions.destroy', $promotion) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this promotion?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-semibold">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            No promotions available yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $promotions->links() }}
    </div>
</div>
@endsection












