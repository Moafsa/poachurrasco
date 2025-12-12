@extends('layouts.app')

@section('title', 'A História de Porto Alegre - Capital Mundial do Churrasco')

@push('meta')
    <meta name="description" content="Conheça a rica história de Porto Alegre e como a cidade se tornou a Capital Mundial do Churrasco. Desde a fundação em 1772 até o reconhecimento oficial em 2023.">
    <meta property="og:image" content="{{ asset('images/hero-porto-alegre.jpg') }}">
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-orange-900 via-orange-800 to-red-900 text-white py-16 sm:py-20 md:py-24 overflow-hidden">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="absolute inset-0" style="background-image: url('https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; opacity: 0.2;"></div>
        <div class="relative max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto">
                <div class="mb-6">
                    <span class="inline-flex items-center gap-2 rounded-full bg-orange-500/20 px-4 py-2 text-sm font-semibold uppercase tracking-widest text-orange-200 backdrop-blur-sm">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        Porto Alegre
                    </span>
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                    A História de Porto Alegre
                </h1>
                <p class="text-lg sm:text-xl md:text-2xl text-orange-100 max-w-3xl mx-auto leading-relaxed">
                    Da fundação em 1772 à Capital Mundial do Churrasco em 2023
                </p>
            </div>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Uma Cidade de Tradição e Inovação</h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-orange-600 to-red-500 mx-auto"></div>
                </div>
                
                <div class="prose prose-lg max-w-none">
                    <p class="text-gray-700 text-lg leading-relaxed mb-6">
                        Porto Alegre, capital do Rio Grande do Sul, tem uma história rica e fascinante que remonta ao século XVIII. Localizada estrategicamente às margens do Lago Guaíba, a cidade se desenvolveu como um importante centro comercial e cultural, atraindo imigrantes de diversas origens que contribuíram para sua diversidade única.
                    </p>
                    
                    <p class="text-gray-700 text-lg leading-relaxed mb-6">
                        Mas é na tradição do churrasco que Porto Alegre encontrou sua identidade mais marcante. Com mais de 100 churrascarias registradas e a maior concentração de churrasqueiras por habitante do Brasil, a cidade consolidou-se como referência mundial na arte de assar carnes, culminando no reconhecimento oficial como "Capital Mundial do Churrasco" em 2023.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Linha do Tempo</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-orange-600 to-red-500 mx-auto mb-8"></div>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Os principais marcos da história de Porto Alegre e sua jornada até se tornar a Capital Mundial do Churrasco
                </p>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="relative">
                    <!-- Timeline line -->
                    <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gradient-to-b from-orange-500 via-orange-400 to-red-500 hidden md:block"></div>
                    
                    <div class="space-y-12">
                        @foreach($timeline as $index => $event)
                        <div class="relative flex items-start md:items-center">
                            <!-- Year badge -->
                            <div class="flex-shrink-0 w-16 h-16 rounded-full bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center text-white font-bold text-sm shadow-lg z-10 relative">
                                <span class="text-center px-2">{{ $event['year'] }}</span>
                            </div>
                            
                            <!-- Content card -->
                            <div class="ml-6 md:ml-8 flex-1 bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition-shadow">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $event['title'] }}</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $event['description'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BBQ Highlights Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Capital Mundial do Churrasco</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-orange-600 to-red-500 mx-auto mb-8"></div>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Como Porto Alegre se tornou referência mundial na arte do churrasco
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-6xl mx-auto">
                @foreach($bbqHighlights as $highlight)
                <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-xl p-8 shadow-md hover:shadow-xl transition-shadow border border-orange-100">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $highlight['title'] }}</h3>
                    </div>
                    <p class="text-gray-700 leading-relaxed">{{ $highlight['description'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-br from-orange-900 via-orange-800 to-red-900 text-white">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">Porto Alegre em Números</h2>
                <div class="w-24 h-1 bg-orange-400 mx-auto"></div>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-5xl mx-auto">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center border border-white/20">
                    <div class="text-4xl font-bold text-orange-300 mb-2">100+</div>
                    <div class="text-orange-100">Churrascarias Registradas</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center border border-white/20">
                    <div class="text-4xl font-bold text-orange-300 mb-2">#1</div>
                    <div class="text-orange-100">Maior Número de Churrasqueiras por Habitante</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center border border-white/20">
                    <div class="text-4xl font-bold text-orange-300 mb-2">4.000+</div>
                    <div class="text-orange-100">Visitantes na Primeira ExpoChurrasco</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center border border-white/20">
                    <div class="text-4xl font-bold text-orange-300 mb-2">2023</div>
                    <div class="text-orange-100">Ano do Reconhecimento Oficial</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cultural Heritage Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Patrimônio Cultural</h2>
                    <div class="w-24 h-1 bg-gradient-to-r from-orange-600 to-red-500 mx-auto"></div>
                </div>
                
                <div class="prose prose-lg max-w-none">
                    <p class="text-gray-700 text-lg leading-relaxed mb-6">
                        O churrasco em Porto Alegre vai muito além de uma simples técnica culinária. É uma expressão cultural que une famílias, amigos e comunidades em torno do fogo, preservando tradições centenárias dos gaúchos. Cada corte de carne, cada técnica de preparo, cada momento compartilhado ao redor da churrasqueira faz parte de um patrimônio imaterial que define a identidade porto-alegrense.
                    </p>
                    
                    <p class="text-gray-700 text-lg leading-relaxed mb-6">
                        A iniciativa de tornar Porto Alegre a Capital Mundial do Churrasco não apenas reconhece essa tradição, mas também promove o turismo gastronômico, fortalece a economia local e preserva esse legado para as futuras gerações. O selo oficial, disponível gratuitamente para estabelecimentos e entidades, simboliza esse compromisso coletivo com a valorização da cultura gaúcha.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sources Section -->
    <section class="py-12 sm:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-md p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Fontes e Referências</h3>
                    <ul class="space-y-4">
                        @foreach($sources as $source)
                        <li>
                            <a href="{{ $source['url'] }}" target="_blank" rel="noopener noreferrer" class="flex items-start text-orange-600 hover:text-orange-700 transition-colors">
                                <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                <span class="hover:underline">{{ $source['label'] }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-r from-orange-500 to-red-600 text-white">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl sm:text-4xl font-bold mb-6">Explore Porto Alegre</h2>
                <p class="text-xl text-orange-100 mb-8 leading-relaxed">
                    Descubra os melhores estabelecimentos, produtos e experiências de churrasco na Capital Mundial do Churrasco
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('mapa') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-orange-600 rounded-xl font-semibold text-lg hover:bg-orange-50 transition-all shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Ver Mapa Interativo
                    </a>
                    <a href="{{ route('products') }}" class="inline-flex items-center justify-center px-8 py-4 bg-orange-600 text-white rounded-xl font-semibold text-lg hover:bg-orange-700 transition-all shadow-lg hover:shadow-xl border-2 border-white/20">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Explorar Marketplace
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection










