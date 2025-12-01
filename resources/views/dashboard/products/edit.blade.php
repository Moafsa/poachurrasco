@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <a href="{{ route('products.show', $product) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to product
        </a>
        <h1 class="mt-4 text-3xl font-bold text-gray-900">Edit product</h1>
        <p class="text-gray-600 mt-2">Update pricing, availability and presentation.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('dashboard.products.partials.form', [
        'action' => route('products.update', $product),
        'method' => 'PUT',
        'submitLabel' => 'Update product',
        'product' => $product,
        'establishments' => $establishments,
        'categories' => $categories,
    ])
</div>
@endsection












