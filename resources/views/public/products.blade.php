@extends('layouts.app')

@section('title', 'Marketplace de Produtos')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-orange-600 via-red-700 to-orange-800 text-white py-16 animate-gradient">
        <div class="container mx-auto px-4 text-center">
            <div class="animate-fadeInUp">
                <h1 class="text-4xl lg:text-6xl font-black mb-6">Marketplace</h1>
                <p class="text-xl lg:text-2xl text-orange-100 max-w-3xl mx-auto">
                    Encontre os melhores produtos para churrasco diretamente dos produtores gaúchos
                </p>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white shadow-sm sticky top-20 z-40">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Category Filters -->
                <div class="flex flex-wrap gap-2">
                    <button class="bg-orange-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-orange-600 transition-colors duration-200">
                        Todos
                    </button>
                    <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-200 transition-colors duration-200">
                        Carnes
                    </button>
                    <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-200 transition-colors duration-200">
                        Temperos
                    </button>
                    <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-200 transition-colors duration-200">
                        Acessórios
                    </button>
                    <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-200 transition-colors duration-200">
                        Bebidas
                    </button>
                </div>
                
                <!-- Sort Options -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Ordenar por:</span>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="popular">Mais Populares</option>
                        <option value="price-low">Menor Preço</option>
                        <option value="price-high">Maior Preço</option>
                        <option value="rating">Melhor Avaliados</option>
                        <option value="newest">Mais Recentes</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Price Filter -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 animate-fadeInLeft">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Faixa de Preço</h3>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="radio" name="price" value="all" class="mr-3 text-orange-500 focus:ring-orange-500">
                            <span class="text-gray-700">Todos os preços</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price" value="0-50" class="mr-3 text-orange-500 focus:ring-orange-500">
                            <span class="text-gray-700">R$ 0 - R$ 50</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price" value="50-100" class="mr-3 text-orange-500 focus:ring-orange-500">
                            <span class="text-gray-700">R$ 50 - R$ 100</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price" value="100-200" class="mr-3 text-orange-500 focus:ring-orange-500">
                            <span class="text-gray-700">R$ 100 - R$ 200</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price" value="200+" class="mr-3 text-orange-500 focus:ring-orange-500">
                            <span class="text-gray-700">Acima de R$ 200</span>
                        </label>
                    </div>
                </div>

                <!-- Brand Filter -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 animate-fadeInLeft stagger-1">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Marcas</h3>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-3 text-orange-500 focus:ring-orange-500">
                            <span class="text-gray-700">Friboi</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-3 text-orange-500 focus:ring-orange-500">
                            <span class="text-gray-700">Seara</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-3 text-orange-500 focus:ring-orange-500">
                            <span class="text-gray-700">Swift</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-3 text-orange-500 focus:ring-orange-500">
                            <span class="text-gray-700">Marfrig</span>
                        </label>
                    </div>
                </div>

                <!-- Rating Filter -->
                <div class="bg-white rounded-2xl shadow-lg p-6 animate-fadeInLeft stagger-2">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Avaliação</h3>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-3 text-orange-500 focus:ring-orange-500">
                            <div class="flex items-center">
                                <div class="flex text-orange-500">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                                <span class="ml-2 text-gray-700">5 estrelas</span>
                            </div>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-3 text-orange-500 focus:ring-orange-500">
                            <div class="flex items-center">
                                <div class="flex text-orange-500">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                                <span class="ml-2 text-gray-700">4 estrelas</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="lg:col-span-3">
                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <!-- Product 1 -->
                    <div class="bg-white rounded-2xl shadow-lg hover-lift transition-all duration-300 overflow-hidden animate-fadeInUp stagger-1">
                        <div class="relative h-48 bg-gradient-to-br from-orange-200 to-red-300">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <div class="text-5xl mb-2 animate-float">🥩</div>
                                    <div class="text-sm opacity-80">Picanha Premium</div>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4">
                                <button class="bg-white bg-opacity-20 backdrop-blur-sm text-white p-2 rounded-full hover:bg-opacity-30 transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="absolute top-4 left-4">
                                <span class="bg-green-500 text-white px-2 py-1 rounded text-xs font-semibold">-20%</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Picanha Angus Premium</h3>
                            <p class="text-gray-600 text-sm mb-3">Corte nobre de primeira qualidade, ideal para churrasco</p>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center text-orange-500">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span class="font-semibold text-sm">4.8</span>
                                    <span class="text-gray-500 ml-1 text-sm">(124)</span>
                                </div>
                                <div class="text-sm text-gray-500">Friboi</div>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-gray-900">R$ 89,90</span>
                                    <span class="text-sm text-gray-500 line-through ml-2">R$ 112,90</span>
                                </div>
                                <div class="text-sm text-gray-500">1kg</div>
                            </div>
                            <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                                Adicionar ao Carrinho
                            </button>
                        </div>
                    </div>

                    <!-- Product 2 -->
                    <div class="bg-white rounded-2xl shadow-lg hover-lift transition-all duration-300 overflow-hidden animate-fadeInUp stagger-2">
                        <div class="relative h-48 bg-gradient-to-br from-red-200 to-orange-300">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <div class="text-5xl mb-2 animate-float">🍖</div>
                                    <div class="text-sm opacity-80">Costela</div>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4">
                                <button class="bg-white bg-opacity-20 backdrop-blur-sm text-white p-2 rounded-full hover:bg-opacity-30 transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Costela de Vaca</h3>
                            <p class="text-gray-600 text-sm mb-3">Costela macia e saborosa, perfeita para churrasco</p>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center text-orange-500">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span class="font-semibold text-sm">4.6</span>
                                    <span class="text-gray-500 ml-1 text-sm">(89)</span>
                                </div>
                                <div class="text-sm text-gray-500">Seara</div>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-gray-900">R$ 45,90</span>
                                </div>
                                <div class="text-sm text-gray-500">1kg</div>
                            </div>
                            <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                                Adicionar ao Carrinho
                            </button>
                        </div>
                    </div>

                    <!-- Product 3 -->
                    <div class="bg-white rounded-2xl shadow-lg hover-lift transition-all duration-300 overflow-hidden animate-fadeInUp stagger-3">
                        <div class="relative h-48 bg-gradient-to-br from-amber-200 to-orange-300">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <div class="text-5xl mb-2 animate-float">🧂</div>
                                    <div class="text-sm opacity-80">Temperos</div>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4">
                                <button class="bg-white bg-opacity-20 backdrop-blur-sm text-white p-2 rounded-full hover:bg-opacity-30 transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Kit Temperos Gaúchos</h3>
                            <p class="text-gray-600 text-sm mb-3">Sal grosso, pimenta e ervas especiais para churrasco</p>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center text-orange-500">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span class="font-semibold text-sm">4.9</span>
                                    <span class="text-gray-500 ml-1 text-sm">(156)</span>
                                </div>
                                <div class="text-sm text-gray-500">Temperos RS</div>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-gray-900">R$ 24,90</span>
                                </div>
                                <div class="text-sm text-gray-500">Kit</div>
                            </div>
                            <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                                Adicionar ao Carrinho
                            </button>
                        </div>
                    </div>

                    <!-- Product 4 -->
                    <div class="bg-white rounded-2xl shadow-lg hover-lift transition-all duration-300 overflow-hidden animate-fadeInUp stagger-4">
                        <div class="relative h-48 bg-gradient-to-br from-green-200 to-green-400">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <div class="text-5xl mb-2 animate-float">🍺</div>
                                    <div class="text-sm opacity-80">Cerveja</div>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4">
                                <button class="bg-white bg-opacity-20 backdrop-blur-sm text-white p-2 rounded-full hover:bg-opacity-30 transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Cerveja Artesanal Gaúcha</h3>
                            <p class="text-gray-600 text-sm mb-3">Cerveja especial para acompanhar churrasco</p>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center text-orange-500">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span class="font-semibold text-sm">4.7</span>
                                    <span class="text-gray-500 ml-1 text-sm">(203)</span>
                                </div>
                                <div class="text-sm text-gray-500">Cervejaria RS</div>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-gray-900">R$ 12,90</span>
                                </div>
                                <div class="text-sm text-gray-500">350ml</div>
                            </div>
                            <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                                Adicionar ao Carrinho
                            </button>
                        </div>
                    </div>

                    <!-- Product 5 -->
                    <div class="bg-white rounded-2xl shadow-lg hover-lift transition-all duration-300 overflow-hidden animate-fadeInUp stagger-5">
                        <div class="relative h-48 bg-gradient-to-br from-yellow-200 to-orange-300">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <div class="text-5xl mb-2 animate-float">🔥</div>
                                    <div class="text-sm opacity-80">Churrasqueira</div>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4">
                                <button class="bg-white bg-opacity-20 backdrop-blur-sm text-white p-2 rounded-full hover:bg-opacity-30 transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Churrasqueira Portátil</h3>
                            <p class="text-gray-600 text-sm mb-3">Churrasqueira compacta para qualquer lugar</p>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center text-orange-500">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span class="font-semibold text-sm">4.5</span>
                                    <span class="text-gray-500 ml-1 text-sm">(67)</span>
                                </div>
                                <div class="text-sm text-gray-500">Churrasqueiras RS</div>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-gray-900">R$ 299,90</span>
                                </div>
                                <div class="text-sm text-gray-500">Unidade</div>
                            </div>
                            <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                                Adicionar ao Carrinho
                            </button>
                        </div>
                    </div>

                    <!-- Product 6 -->
                    <div class="bg-white rounded-2xl shadow-lg hover-lift transition-all duration-300 overflow-hidden animate-fadeInUp stagger-6">
                        <div class="relative h-48 bg-gradient-to-br from-purple-200 to-pink-300">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <div class="text-5xl mb-2 animate-float">🥓</div>
                                    <div class="text-sm opacity-80">Linguiça</div>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4">
                                <button class="bg-white bg-opacity-20 backdrop-blur-sm text-white p-2 rounded-full hover:bg-opacity-30 transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Linguiça Gaúcha</h3>
                            <p class="text-gray-600 text-sm mb-3">Linguiça artesanal com temperos tradicionais</p>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center text-orange-500">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span class="font-semibold text-sm">4.8</span>
                                    <span class="text-gray-500 ml-1 text-sm">(145)</span>
                                </div>
                                <div class="text-sm text-gray-500">Açougue RS</div>
                            </div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-gray-900">R$ 18,90</span>
                                </div>
                                <div class="text-sm text-gray-500">500g</div>
                            </div>
                            <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                                Adicionar ao Carrinho
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Load More -->
                <div class="text-center mt-12 animate-fadeInUp">
                    <button class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                        Carregar Mais Produtos
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

