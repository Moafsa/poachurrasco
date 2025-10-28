@extends('layouts.app')

@section('title', 'Guias de Churrasco')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold bbq-text-gradient mb-4">Guias de Churrasco</h1>
        <p class="text-lg text-gray-600">Aprenda diferentes técnicas, cortes e temperos para fazer o melhor churrasco</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('recipes.guides') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Digite o que procura..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">
            </div>

            <!-- Meat Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Carne</label>
                <select name="meat_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">
                    <option value="">Todos os tipos</option>
                    @foreach($meatTypes as $type)
                    <option value="{{ $type }}" {{ request('meat_type') == $type ? 'selected' : '' }}>
                        {{ ucfirst($type) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Difficulty -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dificuldade</label>
                <select name="difficulty" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500">
                    <option value="">Todas as dificuldades</option>
                    @foreach($difficultyLevels as $key => $label)
                    <option value="{{ $key }}" {{ request('difficulty') == $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-brown-600 text-white px-4 py-2 rounded-md hover:bg-brown-700 transition-colors">
                    <i class="fas fa-search mr-2"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Results Count -->
    @if(request()->hasAny(['search', 'meat_type', 'difficulty']))
    <div class="mb-6">
        <p class="text-gray-600">
            Encontrados {{ $guides->total() }} guia(s) 
            @if(request('search'))
                para "{{ request('search') }}"
            @endif
        </p>
    </div>
    @endif

    <!-- Guides Grid -->
    @if($guides->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($guides as $guide)
        <div class="card-hover bg-white rounded-lg shadow-lg overflow-hidden">
            @if($guide->image_url)
            <img src="{{ $guide->image_url }}" alt="{{ $guide->title }}" class="w-full h-48 object-cover">
            @else
            <div class="w-full h-48 bbq-gradient flex items-center justify-center">
                <i class="fas fa-fire text-white text-4xl"></i>
            </div>
            @endif
            
            <div class="p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="px-3 py-1 text-sm font-semibold rounded-full difficulty-{{ $guide->difficulty_level }}">
                        {{ $guide->difficulty_label }}
                    </span>
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-drumstick-bite mr-1"></i>
                        {{ ucfirst($guide->meat_type) }}
                    </div>
                </div>
                
                <h3 class="text-xl font-semibold mb-3">{{ $guide->title }}</h3>
                <p class="text-gray-600 mb-4">{{ Str::limit($guide->description, 120) }}</p>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-clock mr-2"></i>
                        <span>{{ $guide->cooking_time }}</span>
                    </div>
                    @if($guide->servings)
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-users mr-2"></i>
                        <span>{{ $guide->servings }} porções</span>
                    </div>
                    @endif
                    @if($guide->calories_per_100g)
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-fire mr-2"></i>
                        <span>{{ $guide->calories_per_100g }} cal/100g</span>
                    </div>
                    @endif
                </div>
                
                <a href="{{ route('recipes.guide.show', $guide) }}" 
                   class="block w-full bg-brown-600 text-white text-center py-2 rounded-md hover:bg-brown-700 transition-colors">
                    <i class="fas fa-book-open mr-2"></i> Ver Guia Completo
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $guides->appends(request()->query())->links() }}
    </div>
    @else
    <!-- No Results -->
    <div class="text-center py-12">
        <div class="bbq-gradient w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-search text-white text-3xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum guia encontrado</h3>
        <p class="text-gray-600 mb-6">Tente ajustar os filtros ou buscar por outros termos</p>
        <a href="{{ route('recipes.guides') }}" class="bg-brown-600 text-white px-6 py-3 rounded-md hover:bg-brown-700 transition-colors">
            <i class="fas fa-refresh mr-2"></i> Limpar Filtros
        </a>
    </div>
    @endif
</div>
@endsection
