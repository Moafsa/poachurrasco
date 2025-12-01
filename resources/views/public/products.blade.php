@extends('layouts.app')

@section('title', 'Marketplace')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-orange-600 via-red-700 to-orange-800 text-white py-16">
        <div class="mx-auto max-w-7xl px-4 text-center">
                <h1 class="text-4xl lg:text-6xl font-black mb-6">Marketplace</h1>
                <p class="text-xl lg:text-2xl text-orange-100 max-w-3xl mx-auto">
                Encontre os melhores produtos de churrasco diretamente dos produtores gaúchos
                </p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white shadow-sm sticky top-20 z-40 border-b">
        <div class="mx-auto max-w-7xl px-4 py-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Category Filters -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('products') }}" class="{{ empty($selectedCategory) ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700' }} px-4 py-2 rounded-lg font-semibold hover:bg-orange-600 hover:text-white transition-colors duration-200">
                        Todos
                    </a>
                    @foreach ($categories as $category)
                        <a href="{{ route('products', array_filter(['category' => $category, 'sort' => $selectedSort !== 'popular' ? $selectedSort : null])) }}"
                           class="{{ $selectedCategory === $category ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700' }} px-4 py-2 rounded-lg font-semibold hover:bg-orange-600 hover:text-white transition-colors duration-200">
                            {{ ucfirst($category) }}
                        </a>
                    @endforeach
                </div>
                <!-- Sort Options -->
                <form method="GET" class="flex items-center space-x-4">
                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                    <span class="text-sm text-gray-600">Ordenar por:</span>
                    <select name="sort" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" onchange="this.form.submit()">
                        <option value="popular" @selected($selectedSort === 'popular')>Mais Popular</option>
                        <option value="price-low" @selected($selectedSort === 'price-low')>Menor Preço</option>
                        <option value="price-high" @selected($selectedSort === 'price-high')>Maior Preço</option>
                        <option value="rating" @selected($selectedSort === 'rating')>Melhor Avaliado</option>
                        <option value="newest" @selected($selectedSort === 'newest')>Mais Recente</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="mx-auto max-w-7xl px-4 py-12">
        @if($products->count() > 0)
                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <article class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <div class="relative h-48 bg-gradient-to-br from-orange-200 to-red-300">
                            @if($product->images && count($product->images) > 0)
                                <img 
                                    src="{{ Storage::disk('public')->url($product->images[0]) }}" 
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                >
                            @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center text-white">
                                        <div class="text-5xl mb-2">🥩</div>
                                        <div class="text-sm opacity-80">{{ $product->name }}</div>
                                </div>
                            </div>
                            @endif
                            @if($product->is_featured)
                            <div class="absolute top-4 left-4">
                                    <span class="bg-orange-500 text-white px-2 py-1 rounded text-xs font-semibold">Destaque</span>
                            </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->description ?? 'Produto premium de churrasco.', 80) }}</p>
                            <div class="flex items-center justify-between mb-3">
                                @if($product->rating > 0)
                                <div class="flex items-center text-orange-500">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                        <span class="font-semibold text-sm">{{ number_format($product->rating, 1) }}</span>
                                        @if($product->review_count > 0)
                                            <span class="text-gray-500 ml-1 text-sm">({{ $product->review_count }})</span>
                                        @endif
                                </div>
                                @endif
                                @if($product->establishment)
                                    <div class="text-sm text-gray-500">{{ $product->establishment->name }}</div>
                                @endif
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-gray-900">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                                </div>
                                @if($product->is_active)
                                    <div class="text-sm text-green-600 font-semibold">Em Estoque</div>
                                @else
                                    <div class="text-sm text-red-600 font-semibold">Indisponível</div>
                                @endif
                            </div>
                            <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                                Adicionar ao Carrinho
                            </button>
                        </div>
                    </article>
                @endforeach
                    </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->links() }}
                                </div>
        @else
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="text-6xl mb-4">🛒</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Nenhum produto disponível</h3>
                <p class="text-gray-600">Os produtos aparecerão aqui assim que forem publicados no painel.</p>
                            </div>
        @endif
    </div>
</div>
@endsection
