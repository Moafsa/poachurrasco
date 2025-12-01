@extends('layouts.app')

@section('title', 'Product Analytics')

@section('content')
@php
    $formatNumber = fn ($value) => number_format($value ?? 0, 0, ',', '.');
    $formatCurrency = fn ($value) => 'R$ ' . number_format($value ?? 0, 2, ',', '.');
    $formatDecimal = fn ($value, $decimals = 2) => number_format($value ?? 0, $decimals, ',', '.');
@endphp
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to products
            </a>
            <h1 class="mt-4 text-3xl font-bold text-gray-900">Inventory Analytics</h1>
            <p class="text-gray-600 mt-2">Monitor stock health, performance and engagement across your catalog.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-churrasco-600 text-white rounded-xl hover:bg-churrasco-700 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New product
            </a>
        </div>
    </div>

    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Inventory overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <p class="text-sm text-gray-500">Total products</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $formatNumber($metrics['total_products']) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $formatNumber($metrics['active_products']) }} active · {{ $formatNumber($metrics['inactive_products']) }} inactive</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <p class="text-sm text-gray-500">Stock units</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $formatNumber($metrics['total_units']) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $formatNumber($metrics['low_stock_products']) }} products require restock</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <p class="text-sm text-gray-500">Inventory value</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $formatCurrency($metrics['stock_value']) }}</p>
                <p class="text-sm text-gray-500 mt-1">Average price {{ $formatCurrency($metrics['average_price']) }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <p class="text-sm text-gray-500">Engagement</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $formatNumber($metrics['total_views']) }} views</p>
                <p class="text-sm text-gray-500 mt-1">{{ $formatCurrency($metrics['revenue']) }} estimated revenue</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Top sellers</h3>
                <span class="text-sm text-gray-500">Based on purchase count</span>
            </div>
            <div class="p-6">
                @if($topSellers->isEmpty())
                    <p class="text-sm text-gray-500">No sales recorded yet.</p>
                @else
                    <ul class="space-y-4">
                        @foreach($topSellers as $product)
                            <li class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $product->establishment->name ?? 'Shared catalog' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900">{{ $product->purchase_count }} orders</p>
                                    <p class="text-xs text-gray-500">{{ $formatCurrency($product->price * $product->purchase_count) }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Most viewed</h3>
                <span class="text-sm text-gray-500">Top engagement in the last period</span>
            </div>
            <div class="p-6">
                @if($topViewed->isEmpty())
                    <p class="text-sm text-gray-500">No view data available yet.</p>
                @else
                    <ul class="space-y-4">
                        @foreach($topViewed as $product)
                            <li class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $product->category }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900">{{ $formatNumber($product->view_count) }} views</p>
                                    <p class="text-xs text-gray-500">{{ $product->purchase_count }} purchases</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Low stock alert</h3>
                <span class="text-sm text-gray-500">Prioritize replenishment</span>
            </div>
            <div class="p-6">
                @if($lowStockProducts->isEmpty())
                    <p class="text-sm text-gray-500">All tracked products are above threshold.</p>
                @else
                    <ul class="space-y-4">
                        @foreach($lowStockProducts as $product)
                            <li class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-500">Threshold {{ $product->low_stock_threshold ?? 0 }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold {{ $product->stock_quantity <= 0 ? 'text-red-600' : 'text-amber-600' }}">
                                        {{ $product->stock_quantity }} in stock
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $formatCurrency($product->price) }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent additions</h3>
                <span class="text-sm text-gray-500">Latest products in your catalog</span>
            </div>
            <div class="p-6">
                @if($recentProducts->isEmpty())
                    <p class="text-sm text-gray-500">No products registered yet.</p>
                @else
                    <ul class="space-y-4">
                        @foreach($recentProducts as $product)
                            <li class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900">{{ $formatCurrency($product->price) }}</p>
                                    <p class="text-xs text-gray-500">{{ $product->is_active ? 'Active' : 'Inactive' }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Category distribution</h3>
            <span class="text-sm text-gray-500">Volume, views and revenue by category</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Products</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Purchases</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($categoryBreakdown as $category)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ ucfirst($category->category ?? 'Unknown') }}</td>
                            <td class="px-6 py-4 text-right text-sm text-gray-600">{{ $formatNumber($category->total) }}</td>
                            <td class="px-6 py-4 text-right text-sm text-gray-600">{{ $formatNumber($category->views) }}</td>
                            <td class="px-6 py-4 text-right text-sm text-gray-600">{{ $formatNumber($category->purchases) }}</td>
                            <td class="px-6 py-4 text-right text-sm text-gray-600">{{ $formatCurrency($category->revenue) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-500">No category data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection












