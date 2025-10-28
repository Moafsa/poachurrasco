@extends('layouts.app')

@section('title', 'Guia de Churrasco')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-500">
            <li><a href="{{ route('recipes') }}" class="hover:text-brown-600">Receitas</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li><a href="{{ route('recipes.guides') }}" class="hover:text-brown-600">Guias</a></li>
            <li><i class="fas fa-chevron-right text-xs"></i></li>
            <li class="text-gray-900">{{ $guide->title }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                @if($guide->image_url)
                <img src="{{ $guide->image_url }}" alt="{{ $guide->title }}" class="w-full h-64 object-cover rounded-lg mb-6">
                @else
                <div class="w-full h-64 bbq-gradient rounded-lg flex items-center justify-center mb-6">
                    <i class="fas fa-fire text-white text-6xl"></i>
                </div>
                @endif
                
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full difficulty-{{ $guide->difficulty_level }}">
                            {{ $guide->difficulty_label }}
                        </span>
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-drumstick-bite mr-1"></i>{{ ucfirst($guide->meat_type) }}
                        </span>
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-cut mr-1"></i>{{ ucfirst($guide->cut_type) }}
                        </span>
                    </div>
                </div>
                
                <h1 class="text-3xl font-bold bbq-text-gradient mb-4">{{ $guide->title }}</h1>
                <p class="text-lg text-gray-600 mb-6">{{ $guide->description }}</p>
                
                <!-- Quick Info -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-clock text-brown-600 text-xl mb-2"></i>
                        <div class="text-sm text-gray-600">Tempo</div>
                        <div class="font-semibold">{{ $guide->cooking_time }}</div>
                    </div>
                    @if($guide->servings)
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-users text-brown-600 text-xl mb-2"></i>
                        <div class="text-sm text-gray-600">Porções</div>
                        <div class="font-semibold">{{ $guide->servings }}</div>
                    </div>
                    @endif
                    @if($guide->calories_per_100g)
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-fire text-brown-600 text-xl mb-2"></i>
                        <div class="text-sm text-gray-600">Calorias</div>
                        <div class="font-semibold">{{ $guide->calories_per_100g }}/100g</div>
                    </div>
                    @endif
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-thermometer-half text-brown-600 text-xl mb-2"></i>
                        <div class="text-sm text-gray-600">Temperatura</div>
                        <div class="font-semibold text-sm">{{ Str::limit($guide->temperature_guide, 15) }}</div>
                    </div>
                </div>
            </div>

            <!-- Ingredients -->
            @if($guide->ingredients && count($guide->ingredients) > 0)
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold bbq-text-gradient mb-6">
                    <i class="fas fa-list mr-2"></i> Ingredientes
                </h2>
                <ul class="space-y-3">
                    @foreach($guide->ingredients as $ingredient)
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-3"></i>
                        <span class="text-gray-700">{{ $ingredient }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Steps -->
            @if($guide->steps && count($guide->steps) > 0)
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold bbq-text-gradient mb-6">
                    <i class="fas fa-list-ol mr-2"></i> Modo de Preparo
                </h2>
                <div class="space-y-6">
                    @foreach($guide->steps as $index => $step)
                    <div class="flex">
                        <div class="flex-shrink-0 w-8 h-8 bg-brown-600 text-white rounded-full flex items-center justify-center font-bold mr-4">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-700">{{ $step }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Equipment -->
            @if($guide->equipment_needed)
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold bbq-text-gradient mb-6">
                    <i class="fas fa-tools mr-2"></i> Equipamentos Necessários
                </h2>
                <div class="prose max-w-none">
                    {!! nl2br(e($guide->equipment_needed)) !!}
                </div>
            </div>
            @endif

            <!-- Tips -->
            @if($guide->tips)
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold bbq-text-gradient mb-6">
                    <i class="fas fa-lightbulb mr-2"></i> Dicas Especiais
                </h2>
                <div class="prose max-w-none">
                    {!! nl2br(e($guide->tips)) !!}
                </div>
            </div>
            @endif

            <!-- Serving Suggestions -->
            @if($guide->serving_suggestions)
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold bbq-text-gradient mb-6">
                    <i class="fas fa-utensils mr-2"></i> Sugestões de Acompanhamentos
                </h2>
                <div class="prose max-w-none">
                    {!! nl2br(e($guide->serving_suggestions)) !!}
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- AI Chat CTA -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="text-center">
                    <div class="bbq-gradient w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-robot text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Dúvidas sobre este guia?</h3>
                    <p class="text-gray-600 mb-4">Nossa IA gaúcha pode te ajudar com qualquer pergunta!</p>
                    <a href="{{ route('recipes.chat') }}" class="w-full bg-brown-600 text-white py-2 rounded-md hover:bg-brown-700 transition-colors">
                        <i class="fas fa-comments mr-2"></i> Falar com IA
                    </a>
                </div>
            </div>

            <!-- Related Guides -->
            @if($relatedGuides->count() > 0)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold bbq-text-gradient mb-4">Guias Relacionados</h3>
                <div class="space-y-4">
                    @foreach($relatedGuides as $relatedGuide)
                    <div class="flex items-center space-x-3">
                        @if($relatedGuide->image_url)
                        <img src="{{ $relatedGuide->image_url }}" alt="{{ $relatedGuide->title }}" class="w-16 h-16 object-cover rounded">
                        @else
                        <div class="w-16 h-16 bbq-gradient rounded flex items-center justify-center">
                            <i class="fas fa-fire text-white"></i>
                        </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm">{{ Str::limit($relatedGuide->title, 40) }}</h4>
                            <p class="text-xs text-gray-500">{{ ucfirst($relatedGuide->meat_type) }}</p>
                            <a href="{{ route('recipes.guide.show', $relatedGuide) }}" class="text-brown-600 text-xs hover:underline">
                                Ver guia <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
