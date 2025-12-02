@extends('layouts.app')

@section('title', 'Configurações do Sistema')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-black text-gray-900">Configurações do Sistema</h1>
                    <p class="text-gray-600 mt-2">Gerencie as configurações gerais da plataforma</p>
                </div>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar ao Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('system-settings.update') }}" method="POST" class="bg-white rounded-2xl shadow-lg p-8">
            @csrf

            <!-- Estabelecimentos Externos -->
            <div class="mb-8">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Exibição de Estabelecimentos</h2>
                        <p class="text-sm text-gray-600">
                            Controle se estabelecimentos externos (do Google Places) devem ser exibidos nas páginas públicas (Home e Mapa).
                        </p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <label for="show_external_establishments" class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input 
                                        type="checkbox" 
                                        id="show_external_establishments" 
                                        name="show_external_establishments" 
                                        value="1"
                                        {{ $showExternalEstablishments ? 'checked' : '' }}
                                        class="sr-only peer"
                                    >
                                    <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-churrasco-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-churrasco-500"></div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-base font-semibold text-gray-900">
                                        Exibir Estabelecimentos Externos
                                    </div>
                                    <div class="text-sm text-gray-500 mt-1">
                                        Quando ativado, estabelecimentos do Google Places serão exibidos junto com os estabelecimentos cadastrados no sistema.
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            <p class="font-semibold mb-2">Onde isso se aplica:</p>
                            <ul class="list-disc list-inside space-y-1 text-gray-500">
                                <li>Página inicial (Home) - Estabelecimentos em destaque</li>
                                <li>Mapa interativo - Marcadores no mapa</li>
                                <li>Resultados de busca</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('dashboard') }}" class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-churrasco-500 text-white rounded-lg hover:bg-churrasco-600 transition-colors duration-200 font-semibold">
                    Salvar Configurações
                </button>
            </div>
        </form>

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-base font-semibold text-blue-900 mb-1">Sobre Estabelecimentos Externos</h3>
                    <p class="text-sm text-blue-800">
                        Estabelecimentos externos são obtidos automaticamente do Google Places API e podem incluir informações como avaliações, fotos e horários de funcionamento. 
                        Eles são atualizados periodicamente e podem ser importados para o sistema quando necessário.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection





