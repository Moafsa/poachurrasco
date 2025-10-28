@extends('layouts.app')

@section('title', 'POA Capital do Churrasco')

@section('content')
<div class="min-h-screen">
    <!-- Hero Section with Background Images -->
    <section class="hero-section relative flex items-center justify-center overflow-hidden animate-gradient">
        <!-- Background Images -->
        <div class="absolute inset-0 z-0">
            <!-- Main background -->
            <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-red-900 to-gray-800"></div>
            
            <!-- Food images positioned like in the reference -->
            <div class="absolute top-20 right-20 w-32 h-32 opacity-20 animate-float">
                <div class="w-full h-full bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                    <span class="text-4xl">🍟</span>
                </div>
            </div>
            
            <div class="absolute top-1/3 left-16 w-24 h-24 opacity-30 animate-bounce-slow">
                <div class="w-full h-full bg-gradient-to-br from-red-600 to-red-800 rounded-lg flex items-center justify-center">
                    <span class="text-3xl">🥫</span>
                </div>
            </div>
            
            <div class="absolute bottom-20 left-1/4 w-40 h-32 opacity-25 animate-pulse-slow">
                <div class="w-full h-full bg-gradient-to-br from-green-600 to-green-800 rounded-lg flex items-center justify-center">
                    <span class="text-4xl">🥬</span>
                </div>
            </div>
            
            <div class="absolute bottom-32 right-1/3 w-28 h-28 opacity-20 animate-float">
                <div class="w-full h-full bg-gradient-to-br from-orange-400 to-red-600 rounded-full flex items-center justify-center">
                    <span class="text-3xl">🥩</span>
                </div>
            </div>
            
            <!-- Pattern overlay -->
            <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        
        <!-- Content -->
        <div class="relative z-10 container mx-auto px-4 text-center text-white">
            <div class="max-w-4xl mx-auto">
                <!-- Logo/Brand -->
                <div class="mb-8">
                    <div class="mb-4">
                        <div class="text-sm font-bold text-orange-400">POA</div>
                        <div class="text-2xl font-black text-white">Capital do Churrasco</div>
                    </div>
                </div>
                
                <h1 class="text-5xl lg:text-7xl font-black mb-6 leading-tight text-shadow">
                    <span class="block text-white animate-fadeInUp">Descubra o melhor do</span>
                    <span class="block text-orange-400 animate-fadeInUp stagger-1">churrasco</span>
                    <span class="block text-white animate-fadeInUp stagger-2">em</span>
                    <span class="block text-orange-400 animate-fadeInUp stagger-3">Porto Alegre</span>
                </h1>
                
                <p class="text-xl lg:text-2xl text-gray-200 mb-12 max-w-3xl mx-auto leading-relaxed text-shadow animate-fadeInUp stagger-4">
                    Conectamos você aos melhores estabelecimentos, produtos e experiências da cultura gaúcha na capital mundial do churrasco.
                </p>
                
                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto mb-12">
                    <div class="relative">
                        <form action="{{ route('search') }}" method="GET">
                            <div class="flex glass-effect rounded-2xl p-2 shadow-2xl">
                                <input type="text" 
                                       name="q"
                                       placeholder="Busque por churrascarias, produtos..." 
                                       class="flex-1 px-6 py-4 bg-transparent text-white placeholder-gray-300 focus:outline-none text-lg">
                                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    Buscar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-2xl mx-auto">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="text-3xl font-bold text-white mb-2">50+</div>
                        <div class="text-orange-200">Estabelecimentos</div>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                        <div class="text-3xl font-bold text-white mb-2">4.8</div>
                        <div class="text-orange-200">Avaliação</div>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div class="text-3xl font-bold text-white mb-2">1000+</div>
                        <div class="text-orange-200">Produtos</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Establishments Section -->
    <section class="py-24 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-gray-900 mb-4">Estabelecimentos em Destaque</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Descubra os melhores locais para churrasco em Porto Alegre, selecionados pela nossa comunidade.
                </p>
            </div>
            
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Establishment 1 -->
                <div class="bg-white rounded-2xl shadow-lg hover-lift transition-all duration-300 overflow-hidden group animate-fadeInUp stagger-1">
                    <div class="relative h-64 bg-gradient-to-br from-orange-200 to-red-300">
                        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center text-white">
                                <div class="text-6xl mb-4">🥩</div>
                                <div class="text-sm opacity-80">Churrascaria Tradicional</div>
                            </div>
                        </div>
                        <div class="absolute top-4 left-4">
                            <span class="bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Churrascaria</span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <span class="bg-gray-800 text-white px-3 py-1 rounded-full text-sm font-semibold">$$$</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Churrascaria Galpão Crioulo</h3>
                        <p class="text-gray-600 mb-4">Tradição gaúcha com os melhores cortes de carne</p>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center text-orange-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span class="font-semibold">4.8</span>
                            </div>
                            <div class="flex items-center text-gray-500 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                Av. Ipiranga, 1234
                            </div>
                        </div>
                        <a href="{{ route('establishment.details', 1) }}" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-xl font-semibold transition-colors duration-200">
                            Ver Detalhes
                        </a>
                    </div>
                </div>

                <!-- Establishment 2 -->
                <div class="bg-white rounded-2xl shadow-lg hover-lift transition-all duration-300 overflow-hidden group animate-fadeInUp stagger-2">
                    <div class="relative h-64 bg-gradient-to-br from-red-200 to-orange-300">
                        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center text-white">
                                <div class="text-6xl mb-4">🥩</div>
                                <div class="text-sm opacity-80">Carnes Premium</div>
                            </div>
                        </div>
                        <div class="absolute top-4 left-4">
                            <span class="bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Boutique de Carnes</span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <span class="bg-gray-800 text-white px-3 py-1 rounded-full text-sm font-semibold">$$$$</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Boutique da Carne Premium</h3>
                        <p class="text-gray-600 mb-4">Carnes nobres selecionadas para o churrasco perfeito</p>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center text-orange-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span class="font-semibold">4.9</span>
                            </div>
                            <div class="flex items-center text-gray-500 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                Rua da República, 567
                            </div>
                        </div>
                        <a href="{{ route('establishment.details', 1) }}" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-xl font-semibold transition-colors duration-200">
                            Ver Detalhes
                        </a>
                    </div>
                </div>

                <!-- Establishment 3 -->
                <div class="bg-white rounded-2xl shadow-lg hover-lift transition-all duration-300 overflow-hidden group animate-fadeInUp stagger-3">
                    <div class="relative h-64 bg-gradient-to-br from-amber-200 to-orange-300">
                        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center text-white">
                                <div class="text-6xl mb-4">🍺</div>
                                <div class="text-sm opacity-80">Bar & Churrasco</div>
                            </div>
                        </div>
                        <div class="absolute top-4 left-4">
                            <span class="bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold">Bar</span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <span class="bg-gray-800 text-white px-3 py-1 rounded-full text-sm font-semibold">$$</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Bar do Churrasco</h3>
                        <p class="text-gray-600 mb-4">Ambiente descontraído com churrasco e cerveja gelada</p>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center text-orange-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span class="font-semibold">4.6</span>
                            </div>
                            <div class="flex items-center text-gray-500 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                Rua dos Andradas, 890
                            </div>
                        </div>
                        <a href="{{ route('establishment.details', 1) }}" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-xl font-semibold transition-colors duration-200">
                            Ver Detalhes
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <button class="bg-white border-2 border-gray-300 text-gray-700 px-8 py-3 rounded-xl hover:bg-gray-50 transition-colors duration-200 font-semibold">
                    Ver todos os estabelecimentos
                </button>
            </div>
        </div>
    </section>

    <!-- Marketplace Section -->
    <section class="py-24 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-gray-900 mb-4">Produtos em Destaque</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Encontre os melhores produtos para seu churrasco diretamente dos produtores gaúchos.
                </p>
            </div>
            
            <div class="grid lg:grid-cols-4 gap-6">
                <!-- Product 1 -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <div class="relative h-48 bg-gradient-to-br from-red-200 to-orange-300">
                        <div class="absolute inset-0 bg-black bg-opacity-10"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center text-white">
                                <div class="text-5xl mb-2">🥩</div>
                                <div class="text-sm opacity-80">Picanha Premium</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Picanha Premium</h3>
                        <p class="text-gray-600 text-sm mb-3">Corte nobre selecionado para o churrasco perfeito</p>
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center text-orange-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span class="font-semibold text-sm">4.9</span>
                            </div>
                            <div class="text-gray-500 text-sm">por Boutique da Carne</div>
                        </div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-xl font-bold text-gray-900">R$ 89,90</div>
                            <div class="text-sm text-green-600 font-semibold">Frete grátis</div>
                        </div>
                        <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold transition-colors duration-200">
                            Comprar
                        </button>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <div class="relative h-48 bg-gradient-to-br from-red-300 to-red-500">
                        <div class="absolute inset-0 bg-black bg-opacity-10"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center text-white">
                                <div class="text-5xl mb-2">🧂</div>
                                <div class="text-sm opacity-80">Tempero Gaúcho</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Tempero Gaúcho</h3>
                        <p class="text-gray-600 text-sm mb-3">Tempero tradicional para realçar o sabor da carne</p>
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center text-orange-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span class="font-semibold text-sm">4.7</span>
                            </div>
                            <div class="text-gray-500 text-sm">por Temperos RS</div>
                        </div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-xl font-bold text-gray-900">R$ 24,90</div>
                            <div class="text-sm text-green-600 font-semibold">Frete grátis</div>
                        </div>
                        <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold transition-colors duration-200">
                            Comprar
                        </button>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <div class="relative h-48 bg-gradient-to-br from-gray-300 to-gray-500">
                        <div class="absolute inset-0 bg-black bg-opacity-10"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center text-white">
                                <div class="text-5xl mb-2">🔥</div>
                                <div class="text-sm opacity-80">Carvão Premium</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Carvão Premium</h3>
                        <p class="text-gray-600 text-sm mb-3">Carvão de qualidade para churrasco perfeito</p>
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center text-orange-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span class="font-semibold text-sm">4.5</span>
                            </div>
                            <div class="text-gray-500 text-sm">por Churrasco & Cia</div>
                        </div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-xl font-bold text-gray-900">R$ 45,90</div>
                            <div class="text-sm text-green-600 font-semibold">Frete grátis</div>
                        </div>
                        <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold transition-colors duration-200">
                            Comprar
                        </button>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <div class="relative h-48 bg-gradient-to-br from-gray-400 to-gray-600">
                        <div class="absolute inset-0 bg-black bg-opacity-10"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center text-white">
                                <div class="text-5xl mb-2">🔪</div>
                                <div class="text-sm opacity-80">Faca de Churrasco</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Faca de Churrasco</h3>
                        <p class="text-gray-600 text-sm mb-3">Faca profissional para cortes perfeitos</p>
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center text-orange-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span class="font-semibold text-sm">4.8</span>
                            </div>
                            <div class="text-gray-500 text-sm">por Acessórios RS</div>
                        </div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-xl font-bold text-gray-900">R$ 129,90</div>
                            <div class="text-sm text-red-600 font-semibold">Indisponível</div>
                        </div>
                        <button class="w-full bg-gray-300 text-gray-500 py-2 rounded-lg font-semibold cursor-not-allowed">
                            Indisponível
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12 space-x-4">
                <button class="bg-white border-2 border-gray-300 text-gray-700 px-8 py-3 rounded-xl hover:bg-gray-50 transition-colors duration-200 font-semibold">
                    Visitar o Marketplace
                </button>
                <button class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-xl transition-colors duration-200 font-semibold">
                    Seja um Vendedor
                </button>
            </div>
        </div>
    </section>
</div>
@endsection