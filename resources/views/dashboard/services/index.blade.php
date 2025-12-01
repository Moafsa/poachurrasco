@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Services')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Services</h1>
            <p class="text-gray-600 mt-2">Craft premium barbecue experiences and manage availability.</p>
        </div>
        <a href="{{ route('services.create') }}" class="inline-flex items-center px-5 py-3 bg-churrasco-600 text-white rounded-xl shadow hover:bg-churrasco-700 transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Service
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl mb-8">
        <form method="GET" class="p-6 grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500"
                       placeholder="Service name or description">
            </div>
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="category" name="category"
                        class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    <option value="">All</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>
                            {{ ucfirst(str_replace('_', ' ', $category)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status"
                        class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    <option value="">All</option>
                    <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active</option>
                    <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="flex items-end space-x-3">
                <label class="inline-flex items-center">
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
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Capacity</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Duration</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($services as $service)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-4">
                                @if(!empty($service->images))
                                    <img src="{{ Storage::disk('public')->url($service->images[0]) }}" alt="{{ $service->name }}" class="w-12 h-12 rounded-xl object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m4 4H3a2 2 0 01-2-2V7a2 2 0 012-2h18a2 2 0 012 2v8a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $service->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $service->establishment->name ?? 'Shared service' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ ucfirst(str_replace('_', ' ', $service->category)) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $service->capacity ? $service->capacity . ' guests' : '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $service->duration_minutes ? $service->duration_minutes . ' min' : 'Flexible' }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">
                            R$ {{ number_format($service->price, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $service->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($service->is_featured)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        Featured
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center space-x-2">
                                <a href="{{ route('services.show', $service) }}" class="text-sm text-churrasco-600 hover:text-churrasco-700 font-semibold">View</a>
                                <a href="{{ route('services.edit', $service) }}" class="text-sm text-gray-600 hover:text-gray-800 font-semibold">Edit</a>
                                <form action="{{ route('services.destroy', $service) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this service?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-semibold">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No services were registered yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $services->links() }}
    </div>
</div>
@endsection












