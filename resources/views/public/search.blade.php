@extends('layouts.app')

@section('title', 'Buscar Estabelecimentos')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Search Header -->
    <div class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl font-black text-gray-900 mb-6">Buscar Estabelecimentos</h1>
                
                <!-- Search Form -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <form class="space-y-6">
                        <!-- Main Search -->
                        <div class="relative">
                            <input type="text" 
                                   placeholder="Busque por churrascarias, produtos, endereços..." 
                                   class="w-full px-6 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-lg">
                            <button type="submit" class="absolute right-2 top-2 bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-semibold transition-colors duration-200">
                                Buscar
                            </button>
                        </div>
                        
                        <!-- Filters -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Categoria</label>
                                <select class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                    <option value="">Todas as categorias</option>
                                    <option value="churrascaria">Churrascaria</option>
                                    <option value="açougue">Açougue</option>
                                    <option value="supermercado">Supermercado</option>
                                    <option value="restaurante">Restaurante</option>
                                    <option value="bar">Bar</option>
                                    <option value="lanchonete">Lanchonete</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Bairro</label>
                                <select class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                    <option value="">Todos os bairros</option>
                                    <option value="centro">Centro</option>
                                    <option value="moinhos">Moinhos de Vento</option>
                                    <option value="bom-fim">Bom Fim</option>
                                    <option value="cidade-baixa">Cidade Baixa</option>
                                    <option value="rio-branco">Rio Branco</option>
                                    <option value="petropolis">Petrópolis</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Preço</label>
                                <select class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                    <option value="">Qualquer preço</option>
                                    <option value="1">$ - Econômico</option>
                                    <option value="2">$$ - Moderado</option>
                                    <option value="3">$$$ - Caro</option>
                                    <option value="4">$$$$ - Muito Caro</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Advanced Filters -->
                        <div class="border-t border-gray-200 pt-6">
                            <button type="button" class="text-orange-500 hover:text-orange-600 font-semibold flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                </svg>
                                Filtros Avançados
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Results -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Results Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Resultados da Busca</h2>
                    <p class="text-gray-600">Encontrados 24 estabelecimentos</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Ordenar por:</span>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="relevance">Relevância</option>
                        <option value="rating">Avaliação</option>
                        <option value="distance">Distância</option>
                        <option value="price">Preço</option>
                    </select>
                </div>
            </div>

            <!-- Results Grid -->
            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Result 1 -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="flex">
                        <div class="w-32 h-32 bg-gradient-to-br from-orange-200 to-red-300 flex items-center justify-center">
                            <div class="text-center text-white">
                                <div class="text-3xl mb-1">🥩</div>
                                <div class="text-xs opacity-80">Churrascaria</div>
                            </div>
                        </div>
                        <div class="flex-1 p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-xl font-bold text-gray-900">Churrascaria Galpão Crioulo</h3>
                                <div class="flex items-center text-orange-500">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span class="font-semibold">4.8</span>
                                    <span class="text-gray-500 ml-1">(156)</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">Tradição gaúcha com os melhores cortes de carne</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-500 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    Av. Ipiranga, 1234
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-gray-800 text-white px-2 py-1 rounded text-xs font-semibold">$$$</span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">Aberto</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Result 2 -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="flex">
                        <div class="w-32 h-32 bg-gradient-to-br from-red-200 to-orange-300 flex items-center justify-center">
                            <div class="text-center text-white">
                                <div class="text-3xl mb-1">🥩</div>
                                <div class="text-xs opacity-80">Boutique</div>
                            </div>
                        </div>
                        <div class="flex-1 p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-xl font-bold text-gray-900">Boutique da Carne Premium</h3>
                                <div class="flex items-center text-orange-500">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span class="font-semibold">4.9</span>
                                    <span class="text-gray-500 ml-1">(89)</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">Carnes nobres selecionadas para o churrasco perfeito</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-500 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    Rua da República, 567
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-gray-800 text-white px-2 py-1 rounded text-xs font-semibold">$$$$</span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">Aberto</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Result 3 -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="flex">
                        <div class="w-32 h-32 bg-gradient-to-br from-amber-200 to-orange-300 flex items-center justify-center">
                            <div class="text-center text-white">
                                <div class="text-3xl mb-1">🍺</div>
                                <div class="text-xs opacity-80">Bar</div>
                            </div>
                        </div>
                        <div class="flex-1 p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-xl font-bold text-gray-900">Bar do Churrasco</h3>
                                <div class="flex items-center text-orange-500">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span class="font-semibold">4.6</span>
                                    <span class="text-gray-500 ml-1">(203)</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">Ambiente descontraído com churrasco e cerveja gelada</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-500 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    Rua dos Andradas, 890
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-gray-800 text-white px-2 py-1 rounded text-xs font-semibold">$$</span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">Aberto</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Result 4 -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="flex">
                        <div class="w-32 h-32 bg-gradient-to-br from-gray-300 to-gray-500 flex items-center justify-center">
                            <div class="text-center text-white">
                                <div class="text-3xl mb-1">🛒</div>
                                <div class="text-xs opacity-80">Supermercado</div>
                            </div>
                        </div>
                        <div class="flex-1 p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-xl font-bold text-gray-900">Supermercado Churrasco & Cia</h3>
                                <div class="flex items-center text-orange-500">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span class="font-semibold">4.4</span>
                                    <span class="text-gray-500 ml-1">(67)</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">Supermercado especializado em produtos para churrasco</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-500 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    Av. Bento Gonçalves, 890
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="bg-gray-800 text-white px-2 py-1 rounded text-xs font-semibold">$$</span>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">Aberto</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Load More -->
            <div class="text-center mt-12">
                <button class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-xl font-semibold transition-colors duration-200">
                    Carregar Mais Resultados
                </button>
            </div>
        </div>
    </div>
</div>
@endsection


