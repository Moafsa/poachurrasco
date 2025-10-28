@extends('layouts.app')

@section('title', 'Meus Favoritos')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Meus Favoritos</h1>
                    <p class="text-gray-600 mt-2">Estabelecimentos que você salvou</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('mapa') }}" class="bg-churrasco-500 hover:bg-churrasco-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Ver no Mapa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($favorites->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($favorites as $favorite)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <!-- Image Placeholder -->
                        <div class="h-48 bg-gradient-to-br from-churrasco-400 to-churrasco-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-xl font-bold text-gray-900">{{ $favorite->name }}</h3>
                                <button onclick="removeFavorite({{ $favorite->id }})" class="text-red-500 hover:text-red-700 transition-colors duration-200">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                </button>
                            </div>
                            
                            <p class="text-gray-600 mb-3">{{ $favorite->address }}</p>
                            
                            <!-- Rating -->
                            <div class="flex items-center mb-3">
                                <div class="flex text-yellow-400 mr-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= floor($favorite->rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600">{{ $favorite->rating }} ({{ $favorite->review_count }} avaliações)</span>
                            </div>
                            
                            <!-- Category and Price -->
                            <div class="flex justify-between items-center mb-4">
                                <span class="bg-churrasco-100 text-churrasco-800 px-2 py-1 rounded-full text-xs font-semibold">
                                    {{ ucfirst($favorite->category) }}
                                </span>
                                <span class="text-gray-600 font-semibold">{{ $favorite->price_range }}</span>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex space-x-3">
                                <a href="{{ route('establishment.details', $favorite->id) }}" class="flex-1 bg-churrasco-500 hover:bg-churrasco-600 text-white px-4 py-2 rounded-lg text-center font-semibold transition-colors duration-200">
                                    Ver Detalhes
                                </a>
                                <button onclick="showOnMap({{ $favorite->latitude }}, {{ $favorite->longitude }})" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $favorites->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum favorito ainda</h3>
                <p class="text-gray-600 mb-6">Comece explorando estabelecimentos e adicione seus favoritos!</p>
                <a href="{{ route('mapa') }}" class="bg-churrasco-500 hover:bg-churrasco-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200">
                    Explorar Estabelecimentos
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function removeFavorite(establishmentId) {
    if (confirm('Tem certeza que deseja remover este estabelecimento dos favoritos?')) {
        // This would make an AJAX call to remove the favorite
        fetch(`/favorites/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                establishment_id: establishmentId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao remover favorito');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao remover favorito');
        });
    }
}

function showOnMap(latitude, longitude) {
    // Open map page with specific coordinates
    window.open(`{{ route('mapa') }}?lat=${latitude}&lng=${longitude}`, '_blank');
}
</script>
@endsection


