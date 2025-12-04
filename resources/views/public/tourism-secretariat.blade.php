@extends('layouts.app')

@section('title', 'Secretaria de Turismo de Porto Alegre')

@push('meta')
    <meta name="description" content="Conheça a Secretaria de Turismo de Porto Alegre e seus esforços para promover o turismo na capital gaúcha. Parceria com a Expo Churrasco para fortalecer o setor turístico.">
    <meta property="og:image" content="{{ asset('images/hero-porto-alegre.jpg') }}">
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 text-white py-16 sm:py-20 md:py-24">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto">
                <div class="mb-6">
                    <span class="inline-flex items-center gap-2 rounded-full bg-blue-500/20 px-4 py-2 text-sm font-semibold uppercase tracking-widest text-blue-200 backdrop-blur-sm">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        Porto Alegre
                    </span>
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                    Secretaria de Turismo de Porto Alegre
                </h1>
                <p class="text-lg sm:text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto leading-relaxed">
                    Promovendo e fortalecendo o turismo na capital gaúcha através de parcerias estratégicas e iniciativas inovadoras
                </p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Sobre a Secretaria</h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-orange-500 mx-auto"></div>
                </div>
                
                <div class="prose prose-lg max-w-none">
                    <p class="text-gray-700 text-lg leading-relaxed mb-6">
                        A Secretaria de Turismo de Porto Alegre é responsável por planejar, coordenar e executar as políticas públicas de turismo na capital gaúcha. Com uma visão estratégica e inovadora, a secretaria trabalha para posicionar Porto Alegre como um dos principais destinos turísticos do Brasil.
                    </p>
                    
                    <p class="text-gray-700 text-lg leading-relaxed mb-6">
                        Através de parcerias público-privadas, eventos, campanhas promocionais e o desenvolvimento de produtos turísticos, a secretaria busca fortalecer a economia local, gerar empregos e oportunidades de negócios, além de promover a cultura e as tradições gaúchas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission and Vision -->
    <section class="py-12 sm:py-16 md:py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8 md:gap-12">
                <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Missão</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        Promover e desenvolver o turismo em Porto Alegre de forma sustentável e inclusiva, fortalecendo a economia local, preservando a cultura e as tradições gaúchas, e criando experiências memoráveis para visitantes e moradores.
                    </p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Visão</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        Ser referência nacional em gestão turística, posicionando Porto Alegre como destino obrigatório no circuito turístico brasileiro, reconhecida pela excelência em gastronomia, cultura, eventos e hospitalidade.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tourism Efforts -->
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Nossos Esforços</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-orange-500 mx-auto mb-6"></div>
                <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                    A Secretaria de Turismo desenvolve diversas iniciativas para promover e fortalecer o setor turístico de Porto Alegre
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 hover:shadow-lg transition-shadow">
                    <div class="mb-4">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Eventos e Festivais</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Organização e apoio a eventos que colocam Porto Alegre no calendário turístico nacional, como a Expo Churrasco e outros festivais culturais e gastronômicos.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 hover:shadow-lg transition-shadow">
                    <div class="mb-4">
                        <svg class="w-12 h-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Promoção e Marketing</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Campanhas publicitárias nacionais e internacionais para atrair turistas, além de parcerias estratégicas para divulgar os atrativos da cidade.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 hover:shadow-lg transition-shadow">
                    <div class="mb-4">
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Qualificação Profissional</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Programas de capacitação para profissionais do setor turístico, melhorando a qualidade do atendimento e dos serviços oferecidos.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 hover:shadow-lg transition-shadow">
                    <div class="mb-4">
                        <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Estatísticas e Pesquisas</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Monitoramento constante do setor através de pesquisas, estatísticas e indicadores que orientam as políticas públicas de turismo.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 hover:shadow-lg transition-shadow">
                    <div class="mb-4">
                        <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Parcerias Estratégicas</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Estabelecimento de parcerias com empresas, instituições e entidades para desenvolver projetos conjuntos que fortalecem o turismo local.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 hover:shadow-lg transition-shadow">
                    <div class="mb-4">
                        <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Incentivos e Fomento</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Programas de incentivo e apoio a empreendimentos turísticos, promovendo a criação de novos negócios e a modernização dos existentes.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Partnership with Expo Churrasco -->
    <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-br from-orange-500 to-orange-600 text-white">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <div class="mb-8">
                    <h2 class="text-3xl sm:text-4xl font-bold mb-6">Parceria com a Expo Churrasco</h2>
                    <div class="w-24 h-1 bg-white/30 mx-auto mb-6"></div>
                </div>
                
                <div class="prose prose-lg prose-invert max-w-none">
                    <p class="text-xl leading-relaxed mb-6">
                        A Secretaria de Turismo de Porto Alegre mantém uma parceria estratégica com a Expo Churrasco, reconhecendo o churrasco como uma das principais identidades culturais e gastronômicas da capital gaúcha.
                    </p>
                    
                    <p class="text-xl leading-relaxed mb-8">
                        Esta parceria visa promover Porto Alegre como a <strong>Capital Mundial do Churrasco</strong>, fortalecendo o turismo gastronômico, gerando oportunidades para estabelecimentos locais e criando uma experiência única para visitantes que buscam vivenciar a autenticidade da tradição gaúcha.
                    </p>

                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 text-left">
                        <h3 class="text-2xl font-bold mb-4">Objetivos da Parceria</h3>
                        <ul class="space-y-3 text-lg">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-orange-200 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Posicionar Porto Alegre como destino gastronômico de excelência</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-orange-200 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Fortalecer a economia local através do turismo gastronômico</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-orange-200 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Preservar e valorizar a tradição do churrasco gaúcho</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-orange-200 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Criar roteiros e experiências turísticas únicas</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-orange-200 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Gerar oportunidades de negócios para estabelecimentos locais</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- History Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">História e Tradição</h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-orange-500 mx-auto mb-6"></div>
                </div>
                
                <div class="prose prose-lg max-w-none">
                    <p class="text-gray-700 text-lg leading-relaxed mb-6">
                        A Secretaria de Turismo de Porto Alegre tem uma trajetória marcada pelo compromisso com o desenvolvimento sustentável do turismo na capital gaúcha. Ao longo dos anos, a secretaria tem sido protagonista na promoção da cidade como destino turístico, destacando suas belezas naturais, rica cultura e gastronomia única.
                    </p>
                    
                    <p class="text-gray-700 text-lg leading-relaxed mb-6">
                        Porto Alegre, fundada em 1772, possui uma história rica que se reflete em sua arquitetura, tradições e no famoso churrasco gaúcho. A Secretaria de Turismo trabalha para preservar essa identidade cultural enquanto promove a inovação e a modernização do setor turístico.
                    </p>
                    
                    <p class="text-gray-700 text-lg leading-relaxed">
                        A parceria com a Expo Churrasco representa um marco importante na valorização da gastronomia local como atrativo turístico, reconhecendo que o churrasco não é apenas uma refeição, mas uma expressão cultural que conecta pessoas e preserva tradições centenárias.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-12 sm:py-16 md:py-20 bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl sm:text-4xl font-bold mb-6">Venha Conhecer Porto Alegre</h2>
                <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                    Descubra as melhores experiências gastronômicas, culturais e turísticas que a capital gaúcha tem a oferecer
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl font-semibold text-lg hover:from-orange-600 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl">
                        Explorar Estabelecimentos
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    <a href="{{ route('mapa') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white/10 backdrop-blur-sm text-white border border-white/30 rounded-xl font-semibold text-lg hover:bg-white/20 transition-all">
                        Ver Mapa Interativo
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection




