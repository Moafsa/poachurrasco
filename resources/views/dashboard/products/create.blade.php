@extends('layouts.app')

@section('title', 'Cadastrar Produto')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <a href="{{ route('products.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Voltar para lista
        </a>
        <h1 class="mt-4 text-3xl font-bold text-gray-900">Cadastrar Produto</h1>
        <p class="text-gray-600 mt-2">Publique itens do catálogo e acompanhe o estoque e preços.</p>
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
        'action' => route('products.store'),
        'method' => 'POST',
        'submitLabel' => 'Salvar produto',
        'establishments' => $establishments,
        'categories' => $categories,
    ])
</div>
@endsection












