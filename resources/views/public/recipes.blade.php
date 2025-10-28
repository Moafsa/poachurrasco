@extends('layouts.app')

@section('title', 'Receitas de Churrasco')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-orange-600 via-red-700 to-orange-800 text-white py-16 animate-gradient">
        <div class="container mx-auto px-4 text-center">
            <div class="animate-fadeInUp">
                <h1 class="text-4xl lg:text-6xl font-black mb-6">Receitas de Churrasco</h1>
                <p class="text-xl lg:text-2xl text-orange-100 max-w-3xl mx-auto">
                    Aprenda os segredos dos melhores churrasqueiros gaúchos e impressione seus convidados
                </p>
            </div>
        </div>
    </div>

    <!-- Featured Recipe -->
    <div class="container mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-12 hover-lift animate-fadeInUp">
            <div class="lg:flex">
                <div class="lg:w-1/2 bg-gradient-to-br from-orange-200 to-red-300 p-12 flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-20 animate-shimmer"></div>
                    <div class="text-center text-white relative z-10">
                        <div class="text-8xl mb-4 animate-float">🥩</div>
                        <div class="text-lg opacity-80 animate-fadeInUp stagger-1">Receita em Destaque</div>
                    </div>
                </div>
                <div class="lg:w-1/2 p-12">
                    <div class="flex items-center mb-4 animate-fadeInLeft stagger-1">
                        <span class="bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold mr-3 animate-pulse-slow">Destaque</span>
                        <div class="flex items-center text-orange-500">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="font-semibold">4.9</span>
                            <span class="text-gray-500 ml-1">(234 avaliações)</span>
                        </div>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4 animate-fadeInLeft stagger-2">Picanha Perfeita no Churrasco</h2>
                    <p class="text-gray-600 text-lg mb-6 animate-fadeInLeft stagger-3">
                        A receita definitiva para uma picanha suculenta e saborosa, com temperos especiais e técnicas profissionais.
                    </p>
                    <div class="flex items-center justify-between mb-6 animate-fadeInLeft stagger-4">
                        <div class="flex items-center text-gray-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>2h 30min</span>
                        </div>
                        <div class="flex items-center text-gray-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span>8 pessoas</span>
                        </div>
                        <div class="flex items-center text-gray-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span>Difícil</span>
                        </div>
                    </div>
                    <button class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg animate-fadeInLeft stagger-5">
                        Ver Receita Completa
                    </button>
                    
                    <!-- BBQ Portal Integration -->
                    <div class="mt-4 flex space-x-3">
                        <a href="{{ route('recipes.chat') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 text-sm">
                            <i class="fas fa-robot mr-1"></i> IA Gaúcha
                        </a>
                        <a href="{{ route('recipes.calculator') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 text-sm">
                            <i class="fas fa-calculator mr-1"></i> Calculadora
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 animate-fadeInUp">Categorias de Receitas</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-lg p-6 text-center hover-lift cursor-pointer animate-fadeInUp stagger-1">
                    <div class="text-4xl mb-3 animate-bounce-slow">🥩</div>
                    <h3 class="font-semibold text-gray-900">Carnes</h3>
                    <p class="text-sm text-gray-500">24 receitas</p>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 text-center hover-lift cursor-pointer animate-fadeInUp stagger-2">
                    <div class="text-4xl mb-3 animate-bounce-slow">🍖</div>
                    <h3 class="font-semibold text-gray-900">Costelas</h3>
                    <p class="text-sm text-gray-500">18 receitas</p>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 text-center hover-lift cursor-pointer animate-fadeInUp stagger-3">
                    <div class="text-4xl mb-3 animate-bounce-slow">🍗</div>
                    <h3 class="font-semibold text-gray-900">Aves</h3>
                    <p class="text-sm text-gray-500">15 receitas</p>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 text-center hover-lift cursor-pointer animate-fadeInUp stagger-4">
                    <div class="text-4xl mb-3 animate-bounce-slow">🥓</div>
                    <h3 class="font-semibold text-gray-900">Linguiças</h3>
                    <p class="text-sm text-gray-500">12 receitas</p>
                </div>
            </div>
        </div>

        <!-- Recipes Grid -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-8 animate-fadeInUp">
                <h2 class="text-3xl font-bold text-gray-900">Receitas Populares</h2>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Ordenar por:</span>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover-scale">
                        <option value="popular">Mais Populares</option>
                        <option value="recent">Mais Recentes</option>
                        <option value="rating">Melhor Avaliadas</option>
                        <option value="time">Menor Tempo</option>
                    </select>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Recipe 1 -->
                <div class="bg-white rounded-2xl shadow-lg hover-lift transition-all duration-300 overflow-hidden animate-fadeInUp stagger-1">
                    <div class="relative h-48 bg-gradient-to-br from-orange-200 to-red-300">
                        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center text-white">
                                <div class="text-5xl mb-2 animate-float">🥩</div>
                                <div class="text-sm opacity-80">Picanha</div>
                            </div>
                        </div>
                        <div class="absolute top-4 right-4">
                            <div class="flex items-center bg-white bg-opacity-20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm animate-pulse-slow">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                4.8
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Picanha com Sal Grosso</h3>
                        <p class="text-gray-600 mb-4">Receita tradicional gaúcha com tempero simples e sabor autêntico</p>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center text-gray-500 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                2h
                            </div>
                            <div class="flex items-center text-gray-500 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                6 pessoas
                            </div>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">Fácil</span>
                        </div>
                        <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                            Ver Receita
                        </button>
                    </div>
                </div>

                <!-- Recipe 2 -->
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
                            <div class="flex items-center bg-white bg-opacity-20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm animate-pulse-slow">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                4.9
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Costela no Bafo</h3>
                        <p class="text-gray-600 mb-4">Técnica especial para costela macia e saborosa</p>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center text-gray-500 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                4h
                            </div>
                            <div class="flex items-center text-gray-500 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                8 pessoas
                            </div>
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold">Médio</span>
                        </div>
                        <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                            Ver Receita
                        </button>
                    </div>
                </div>

                <!-- Recipe 3 -->
                <div class="bg-white rounded-2xl shadow-lg hover-lift transition-all duration-300 overflow-hidden animate-fadeInUp stagger-3">
                    <div class="relative h-48 bg-gradient-to-br from-amber-200 to-orange-300">
                        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center text-white">
                                <div class="text-5xl mb-2 animate-float">🍗</div>
                                <div class="text-sm opacity-80">Frango</div>
                            </div>
                        </div>
                        <div class="absolute top-4 right-4">
                            <div class="flex items-center bg-white bg-opacity-20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm animate-pulse-slow">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                4.7
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Frango Recheado</h3>
                        <p class="text-gray-600 mb-4">Frango inteiro recheado com temperos especiais</p>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center text-gray-500 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                1h 30min
                            </div>
                            <div class="flex items-center text-gray-500 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                4 pessoas
                            </div>
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold">Difícil</span>
                        </div>
                        <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                            Ver Receita
                        </button>
                    </div>
                </div>
            </div>

            <!-- Load More -->
            <div class="text-center mt-12 animate-fadeInUp">
                <button class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                    Carregar Mais Receitas
                </button>
            </div>
        </div>

        <!-- BBQ Portal Integration Section -->
        <div class="bg-gradient-to-br from-green-600 to-green-800 text-white rounded-2xl shadow-lg p-8 mb-12 hover-lift animate-fadeInUp">
            <div class="text-center">
                <div class="text-6xl mb-4 animate-bounce-slow">🤖</div>
                <h2 class="text-3xl font-bold mb-4">IA Gaúcha - Especialista em Churrasco</h2>
                <p class="text-xl text-green-100 mb-6 max-w-2xl mx-auto">
                    Converse com nossa IA especializada! Envie uma foto do seu prato e ela calculará calorias, dará dicas e muito mais!
                </p>
                
                <!-- Chat Interface -->
                <div class="bg-white rounded-xl p-6 max-w-4xl mx-auto">
                    <div id="chat-container" class="h-96 overflow-y-auto border rounded-lg p-4 mb-4 bg-gray-50">
                        <div class="text-center text-gray-500 py-8">
                            <div class="text-4xl mb-2">🤠</div>
                            <p class="text-lg font-semibold">Oi, tchê!</p>
                            <p>Envie uma foto do seu churrasco ou faça uma pergunta!</p>
                        </div>
                    </div>
                    
                    <!-- Message Input -->
                    <div class="flex space-x-2">
                        <input type="file" id="photo-input" accept="image/*" class="hidden">
                        <button id="photo-btn" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-camera mr-2"></i> Foto
                        </button>
                        <button id="mic-btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-microphone mr-2"></i> Falar
                        </button>
                        <input type="text" id="message-input" placeholder="Digite sua pergunta sobre churrasco..." 
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <button id="send-btn" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    
                    <!-- Quick Questions -->
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <button class="quick-question bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded text-sm transition-colors" data-question="Como fazer uma picanha perfeita?">
                            Como fazer picanha perfeita?
                        </button>
                        <button class="quick-question bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded text-sm transition-colors" data-question="Qual o melhor tempero para costela?">
                            Melhor tempero para costela?
                        </button>
                        <button class="quick-question bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded text-sm transition-colors" data-question="Como calcular quantidade de carne por pessoa?">
                            Quantidade de carne por pessoa?
                        </button>
                        <button class="quick-question bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded text-sm transition-colors" data-question="Qual a temperatura ideal para grelhar?">
                            Temperatura ideal para grelhar?
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tips Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8 hover-lift animate-fadeInUp">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Dicas dos Especialistas</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="flex items-start space-x-4 animate-fadeInLeft stagger-1">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 hover-rotate">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Temperatura Ideal</h3>
                        <p class="text-gray-600">A temperatura ideal para o churrasco é entre 200°C e 250°C. Use um termômetro para garantir o controle preciso.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4 animate-fadeInRight stagger-2">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 hover-rotate">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Tempo de Repouso</h3>
                        <p class="text-gray-600">Sempre deixe a carne repousar por 5-10 minutos após o cozimento para redistribuir os sucos.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4 animate-fadeInLeft stagger-3">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 hover-rotate">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Corte Correto</h3>
                        <p class="text-gray-600">Corte sempre contra as fibras da carne para garantir maciez e facilitar a mastigação.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4 animate-fadeInRight stagger-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 hover-rotate">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Temperos Naturais</h3>
                        <p class="text-gray-600">Use temperos naturais como sal grosso, pimenta e ervas frescas para realçar o sabor natural da carne.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatContainer = document.getElementById('chat-container');
    const messageInput = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-btn');
    const photoBtn = document.getElementById('photo-btn');
    const photoInput = document.getElementById('photo-input');
    const micBtn = document.getElementById('mic-btn');
    const quickQuestions = document.querySelectorAll('.quick-question');

    // Speech recognition and synthesis
    let recognition = null;
    let isListening = false;
    let speechSynthesis = window.speechSynthesis;

    // Initialize speech recognition
    if ('webkitSpeechRecognition' in window) {
        recognition = new webkitSpeechRecognition();
        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = 'pt-BR';

        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            messageInput.value = transcript;
            sendMessage(transcript);
        };

        recognition.onerror = function(event) {
            console.error('Speech recognition error:', event.error);
            micBtn.innerHTML = '<i class="fas fa-microphone mr-2"></i> Falar';
            micBtn.classList.remove('bg-red-600');
            micBtn.classList.add('bg-blue-600');
            isListening = false;
        };

        recognition.onend = function() {
            micBtn.innerHTML = '<i class="fas fa-microphone mr-2"></i> Falar';
            micBtn.classList.remove('bg-red-600');
            micBtn.classList.add('bg-blue-600');
            isListening = false;
        };
    }

    // Add message to chat with typing animation
    function addMessage(content, isAI = false, isImage = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `mb-4 ${isAI ? 'text-left' : 'text-right'}`;
        
        if (isImage) {
            messageDiv.innerHTML = `
                <div class="inline-block max-w-xs">
                    <img src="${content}" alt="Foto enviada" class="rounded-lg shadow-md">
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="inline-block max-w-xs bg-${isAI ? 'white' : 'green-500'} text-${isAI ? 'gray-800' : 'white'} p-3 rounded-lg shadow-md">
                    ${content}
                </div>
            `;
        }
        
        chatContainer.appendChild(messageDiv);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Add AI message with sentence-by-sentence typing
    function addAIMessage(content) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'mb-4 text-left';
        
        const messageContent = document.createElement('div');
        messageContent.className = 'inline-block max-w-xs bg-white text-gray-800 p-3 rounded-lg shadow-md';
        messageContent.innerHTML = '<div class="typing-indicator"><i class="fas fa-user mr-2"></i> IA Gaúcha está digitando...</div>';
        
        messageDiv.appendChild(messageContent);
        chatContainer.appendChild(messageDiv);
        chatContainer.scrollTop = chatContainer.scrollHeight;

        // Split content into sentences more intelligently
        const sentences = content.split(/(?<=[.!?])\s+/).filter(s => s.trim().length > 0);
        let currentText = '';
        let sentenceIndex = 0;

        function typeNextSentence() {
            if (sentenceIndex < sentences.length) {
                const sentence = sentences[sentenceIndex].trim();
                if (sentence) {
                    currentText += sentence + '\n';
                    // Add line break after each sentence
                    messageContent.innerHTML = `<div class="ai-message whitespace-pre-line">${currentText}</div>`;
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                    sentenceIndex++;
                    
                    // Random delay between sentences (simulate human typing)
                    const delay = Math.random() * 800 + 400; // 400-1200ms
                    setTimeout(typeNextSentence, delay);
                } else {
                    sentenceIndex++;
                    typeNextSentence();
                }
            } else {
                // Finished typing, add audio button
                const audioBtn = document.createElement('button');
                audioBtn.className = 'mt-2 bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition-colors';
                audioBtn.innerHTML = '<i class="fas fa-volume-up mr-1"></i> Ouvir';
                audioBtn.onclick = () => speakText(content);
                
                messageContent.appendChild(audioBtn);
                
                // Auto-speak the response
                setTimeout(() => speakText(content), 1000);
            }
        }

        // Start typing animation
        setTimeout(typeNextSentence, 500);
    }

    // Text-to-speech function with better voice
    function speakText(text) {
        if (speechSynthesis) {
            speechSynthesis.cancel(); // Cancel any ongoing speech
            
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'pt-BR';
            utterance.rate = 0.85; // Slower for more natural speech
            utterance.pitch = 0.9; // Slightly lower pitch
            utterance.volume = 0.9;
            
            // Try to use a Brazilian Portuguese voice
            const voices = speechSynthesis.getVoices();
            
            // Look for Brazilian voices first
            let selectedVoice = voices.find(voice => 
                voice.lang === 'pt-BR' && voice.name.includes('Brazil')
            );
            
            // If not found, look for any Portuguese voice
            if (!selectedVoice) {
                selectedVoice = voices.find(voice => 
                    voice.lang.includes('pt-BR') || voice.lang.includes('pt')
                );
            }
            
            // If still not found, use default but with better settings
            if (selectedVoice) {
                utterance.voice = selectedVoice;
            }
            
            // Add pauses between sentences for more natural speech
            const sentences = text.split(/(?<=[.!?])\s+/);
            if (sentences.length > 1) {
                utterance.rate = 0.8; // Even slower for multi-sentence text
            }
            
            speechSynthesis.speak(utterance);
        }
    }

    // Send message to AI
    function sendMessage(message, imageData = null) {
        if (!message && !imageData) return;

        // Add user message
        if (imageData) {
            addMessage(imageData, false, true);
        }
        if (message) {
            addMessage(message);
        }

        // Prepare data
        const formData = new FormData();
        if (message) formData.append('message', message);
        if (imageData) formData.append('image', imageData);

        // Send to server
        fetch('{{ route("recipes.chat.message") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                addAIMessage(data.ai_response.response);
            } else {
                addAIMessage('Desculpa, tchê! Tive um probleminha aqui. Tenta de novo?');
            }
        })
        .catch(error => {
            addAIMessage('Eita! Deu um erro aqui. Tenta de novo, por favor!');
        });
    }

    // Handle photo upload
    photoBtn.addEventListener('click', () => {
        photoInput.click();
    });

    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                sendMessage('Analise esta foto do meu churrasco e me diga sobre calorias, dicas e tudo mais!', file);
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle microphone button
    micBtn.addEventListener('click', () => {
        if (!recognition) {
            alert('Seu navegador não suporta reconhecimento de voz. Use o Chrome ou Edge.');
            return;
        }

        if (isListening) {
            recognition.stop();
            micBtn.innerHTML = '<i class="fas fa-microphone mr-2"></i> Falar';
            micBtn.classList.remove('bg-red-600');
            micBtn.classList.add('bg-blue-600');
            isListening = false;
        } else {
            recognition.start();
            micBtn.innerHTML = '<i class="fas fa-stop mr-2"></i> Parar';
            micBtn.classList.remove('bg-blue-600');
            micBtn.classList.add('bg-red-600');
            isListening = true;
        }
    });

    // Handle send button
    sendBtn.addEventListener('click', () => {
        const message = messageInput.value.trim();
        if (message) {
            sendMessage(message);
            messageInput.value = '';
        }
    });

    // Handle enter key
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendBtn.click();
        }
    });

    // Handle quick questions
    quickQuestions.forEach(button => {
        button.addEventListener('click', function() {
            const question = this.dataset.question;
            messageInput.value = question;
            sendMessage(question);
            messageInput.value = '';
        });
    });

    // Load voices for speech synthesis
    if (speechSynthesis) {
        speechSynthesis.onvoiceschanged = function() {
            // Voices are loaded
        };
    }
});
</script>
@endsection