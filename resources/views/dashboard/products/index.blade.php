@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Products')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Products</h1>
            <p class="text-gray-600 mt-2">Manage the catalog shared across your establishments.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('products.analytics') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3v18m8-8H3"/>
                </svg>
                Analytics
            </a>
            <a href="{{ route('products.create') }}" class="inline-flex items-center px-5 py-3 bg-churrasco-600 text-white rounded-xl shadow hover:bg-churrasco-700 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Product
            </a>
        </div>
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
                       placeholder="Name or description">
            </div>
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="category" name="category" class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
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
                <select id="status" name="status" class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    <option value="">All</option>
                    <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active</option>
                    <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="featured" value="1" @checked(!empty($filters['featured'])) class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
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
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Updated</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-4">
                                @if($product->main_image)
                                    <img src="{{ Storage::disk('public')->url($product->main_image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a4 4 0 00-8 0v6m-2 4h10M6 9h.01M4 13h4m-2 0v6m2-6a2 2 0 012 2v4H4v-4a2 2 0 012-2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $product->establishment->name ?? 'Shared library' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ ucfirst(str_replace('_', ' ', $product->category)) }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-gray-900 font-semibold">
                            {{ $product->formatted_price }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $product->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($product->is_featured)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        Featured
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-gray-500">
                            {{ $product->updated_at?->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center space-x-2">
                                <a href="{{ route('products.show', $product) }}" class="text-sm text-churrasco-600 hover:text-churrasco-700 font-semibold">View</a>
                                <a href="{{ route('products.edit', $product) }}" class="text-sm text-gray-600 hover:text-gray-800 font-semibold">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this product?');">
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
                            No products found. Start by creating your first product.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
@endsection

