@extends('layouts.app')

@section('title', 'Detalhes do Estabelecimento')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="relative h-96 bg-gradient-to-r from-churrasco-600 to-churrasco-800">
        @if($establishment->photo_urls && count($establishment->photo_urls) > 0)
            <div class="absolute inset-0">
                <img src="{{ $establishment->photo_urls[0] }}" 
                     alt="{{ $establishment->name }}" 
                     class="w-full h-full object-cover"
                     crossorigin="anonymous"
                     referrerpolicy="no-referrer"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="absolute inset-0 bg-gradient-to-r from-churrasco-600 to-churrasco-800 flex items-center justify-center" style="display: none;">
                    <div class="text-white text-center">
                        <h1 class="text-4xl font-bold mb-4">{{ $establishment->name }}</h1>
                        <p class="text-xl">{{ $establishment->formatted_address ?? $establishment->address ?? 'Endereço não disponível' }}</p>
                    </div>
                </div>
            </div>
        @endif
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
            <div class="text-white">
                <h1 class="text-4xl font-bold mb-4">{{ $establishment->name }}</h1>
                <p class="text-xl mb-6">{{ $establishment->formatted_address ?? $establishment->address ?? 'Endereço não disponível' }}</p>
                <div class="flex items-center space-x-6">
                    <div class="flex items-center">
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-6 h-6 {{ $i <= floor($establishment->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="ml-2 text-lg font-semibold">{{ number_format($establishment->rating ?? 0, 1) }}</span>
                        <span class="ml-1 text-gray-300">({{ $establishment->user_ratings_total ?? 0 }} avaliações)</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Centro Histórico</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- About Section -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Sobre o Estabelecimento</h2>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        A Churrascaria Gaúcha é um estabelecimento tradicional em Porto Alegre, 
                        especializado em cortes premium de carne e pratos típicos da culinária gaúcha. 
                        Com mais de 20 anos de experiência, oferecemos uma experiência autêntica 
                        do churrasco gaúcho com ingredientes frescos e técnicas tradicionais.
                    </p>
                    
                    <!-- Features -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Estacionamento</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Wi-Fi Gratuito</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Acessibilidade</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Delivery</span>
                        </div>
                    </div>
                </div>

                <!-- Photo Gallery -->
                @if($establishment->photo_urls && count($establishment->photo_urls) > 1)
                <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Galeria de Fotos</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($establishment->photo_urls as $index => $photoUrl)
                            <div class="relative group cursor-pointer" onclick="openPhotoModal({{ $index }})">
                                <img src="{{ $photoUrl }}" alt="{{ $establishment->name }} - Foto {{ $index + 1 }}" 
                                     class="w-full h-32 object-cover rounded-lg hover:opacity-90 transition-opacity duration-200"
                                     crossorigin="anonymous"
                                     referrerpolicy="no-referrer"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-full h-32 bg-gradient-to-br from-churrasco-500 to-churrasco-600 rounded-lg flex items-center justify-center" style="display: none;">
                                    <span class="text-white text-sm font-bold">{{ substr($establishment->name, 0, 1) }}</span>
                                </div>
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                    </svg>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Reviews Section -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Avaliações</h2>
                            <div class="flex items-center mt-2">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= floor($establishment->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="ml-2 text-lg font-semibold">{{ number_format($establishment->rating ?? 0, 1) }}</span>
                                <span class="ml-1 text-gray-500">({{ $establishment->review_count ?? 0 }} avaliações)</span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            @if($establishment->external_id)
                                <button onclick="syncExternalReviews()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
                                    Atualizar Avaliações
                                </button>
                            @endif
                            @auth
                                <button onclick="openReviewModal()" class="bg-churrasco-500 hover:bg-churrasco-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
                                    Escrever Avaliação
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="bg-churrasco-500 hover:bg-churrasco-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
                                    Entrar para Avaliar
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Loading indicator -->
                    <div id="reviewsLoading" class="text-center py-8 hidden">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-churrasco-500"></div>
                        <p class="mt-2 text-gray-600">Carregando avaliações...</p>
                    </div>

                    <!-- Review List -->
                    <div id="reviewsContainer" class="space-y-6">
                        <!-- Reviews will be loaded here via JavaScript -->
                    </div>

                    <!-- Load More Button -->
                    <div class="text-center mt-6">
                        <button onclick="loadMoreReviews()" id="loadMoreBtn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold transition-colors duration-200">
                            Carregar Mais Avaliações
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Contact Info -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informações de Contato</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-gray-700">{{ $establishment->formatted_address ?? $establishment->address ?? 'Endereço não disponível' }}</span>
                        </div>
                        @if($establishment->formatted_phone_number)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-gray-700">{{ $establishment->formatted_phone_number }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Opening Hours -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Horário de Funcionamento</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Segunda - Sexta</span>
                            <span class="text-gray-900 font-medium">11:00 - 23:00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sábado</span>
                            <span class="text-gray-900 font-medium">11:00 - 00:00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Domingo</span>
                            <span class="text-gray-900 font-medium">11:00 - 22:00</span>
                        </div>
                    </div>
                </div>

                <!-- Map -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Localização</h3>
                    @if($establishment->latitude && $establishment->longitude)
                        <div id="map" class="h-48 rounded-lg"></div>
                    @else
                        <div class="h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span class="text-gray-500">Coordenadas não disponíveis</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div id="photoModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closePhotoModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modalPhoto" src="" alt="" class="max-w-full max-h-full rounded-lg">
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white text-center">
                <p id="photoCounter" class="text-sm"></p>
            </div>
            @if($establishment->photo_urls && count($establishment->photo_urls) > 1)
            <button onclick="previousPhoto()" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button onclick="nextPhoto()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Escrever Avaliação</h3>
                <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form method="POST" action="{{ route('reviews.store') }}">
                @csrf
                <input type="hidden" name="establishment_id" value="1">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Avaliação</label>
                    <div class="flex space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" class="star-rating text-gray-300 hover:text-yellow-400 transition-colors duration-200">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating" value="0">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comentário</label>
                    <textarea name="comment" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500" placeholder="Conte sua experiência..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeReviewModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-200">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-churrasco-500 hover:bg-churrasco-600 text-white px-6 py-2 rounded-lg font-semibold transition-colors duration-200">
                        Enviar Avaliação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Photo modal functionality
let currentPhotoIndex = 0;
const photos = @json($establishment->photo_urls ?? []);

function openPhotoModal(index) {
    currentPhotoIndex = index;
    document.getElementById('modalPhoto').src = photos[index];
    document.getElementById('modalPhoto').alt = "{{ $establishment->name }} - Foto " + (index + 1);
    document.getElementById('photoCounter').textContent = (index + 1) + " de " + photos.length;
    document.getElementById('photoModal').classList.remove('hidden');
}

function closePhotoModal() {
    document.getElementById('photoModal').classList.add('hidden');
}

function previousPhoto() {
    if (currentPhotoIndex > 0) {
        currentPhotoIndex--;
        openPhotoModal(currentPhotoIndex);
    }
}

function nextPhoto() {
    if (currentPhotoIndex < photos.length - 1) {
        currentPhotoIndex++;
        openPhotoModal(currentPhotoIndex);
    }
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (!document.getElementById('photoModal').classList.contains('hidden')) {
        if (e.key === 'Escape') {
            closePhotoModal();
        } else if (e.key === 'ArrowLeft') {
            previousPhoto();
        } else if (e.key === 'ArrowRight') {
            nextPhoto();
        }
    }
});

// Reviews functionality
let currentPage = 1;
let isLoading = false;

// Load reviews on page load
document.addEventListener('DOMContentLoaded', function() {
    loadReviews();
});

function loadReviews(page = 1) {
    if (isLoading) return;
    
    isLoading = true;
    document.getElementById('reviewsLoading').classList.remove('hidden');
    
    fetch(`/api/establishments/{{ $establishment->id }}/reviews?page=${page}`)
        .then(response => response.json())
        .then(data => {
            if (page === 1) {
                document.getElementById('reviewsContainer').innerHTML = '';
            }
            
            if (data.reviews && data.reviews.length > 0) {
                data.reviews.forEach(review => {
                    addReviewToContainer(review);
                });
                
                // Update overall rating display
                if (data.overall_rating) {
                    updateOverallRating(data.overall_rating);
                }
                
                // Show/hide load more button
                if (data.reviews.length < 10) {
                    document.getElementById('loadMoreBtn').style.display = 'none';
                } else {
                    document.getElementById('loadMoreBtn').style.display = 'block';
                }
            } else if (page === 1) {
                document.getElementById('reviewsContainer').innerHTML = '<p class="text-gray-500 text-center py-8">Nenhuma avaliação encontrada.</p>';
                document.getElementById('loadMoreBtn').style.display = 'none';
            }
            
            currentPage = page;
        })
        .catch(error => {
            console.error('Error loading reviews:', error);
            if (page === 1) {
                document.getElementById('reviewsContainer').innerHTML = '<p class="text-red-500 text-center py-8">Erro ao carregar avaliações.</p>';
            }
        })
        .finally(() => {
            isLoading = false;
            document.getElementById('reviewsLoading').classList.add('hidden');
        });
}

function loadMoreReviews() {
    loadReviews(currentPage + 1);
}

function addReviewToContainer(review) {
    const container = document.getElementById('reviewsContainer');
    const reviewElement = document.createElement('div');
    reviewElement.className = 'border-b border-gray-200 pb-6 last:border-b-0';
    
    const authorInitials = review.author_name.split(' ').map(name => name.charAt(0)).join('').substring(0, 2).toUpperCase();
    const avatarColor = review.type === 'external' ? 'from-blue-500 to-blue-600' : 'from-churrasco-500 to-churrasco-600';
    
    reviewElement.innerHTML = `
        <div class="flex items-start space-x-4">
            <div class="w-10 h-10 bg-gradient-to-br ${avatarColor} rounded-full flex items-center justify-center">
                ${review.author_avatar ? 
                    `<img src="${review.author_avatar}" alt="${review.author_name}" class="w-10 h-10 rounded-full object-cover">` : 
                    `<span class="text-white font-bold">${authorInitials}</span>`
                }
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <h4 class="font-semibold text-gray-900">${review.author_name}</h4>
                        ${review.type === 'external' ? '<span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Google</span>' : ''}
                    </div>
                    <div class="flex text-yellow-400">
                        ${Array.from({length: 5}, (_, i) => 
                            `<svg class="w-4 h-4 ${i < review.rating ? 'text-yellow-400' : 'text-gray-300'}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>`
                        ).join('')}
                    </div>
                </div>
                <p class="text-gray-600 mb-2">${review.text || 'Sem comentário'}</p>
                <span class="text-sm text-gray-500">${review.time_ago}</span>
            </div>
        </div>
    `;
    
    container.appendChild(reviewElement);
}

function updateOverallRating(ratingData) {
    // Update the rating display in the header
    const ratingElement = document.querySelector('.flex.items-center.space-x-6 .flex.items-center span');
    if (ratingElement) {
        ratingElement.textContent = ratingData.rating.toFixed(1);
    }
    
    // Update the review count
    const countElement = document.querySelector('.flex.items-center.space-x-6 .flex.items-center span:last-child');
    if (countElement) {
        countElement.textContent = `(${ratingData.total_reviews} avaliações)`;
    }
}

function syncExternalReviews() {
    if (isLoading) return;
    
    isLoading = true;
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = 'Atualizando...';
    button.disabled = true;
    
    fetch(`/api/establishments/{{ $establishment->id }}/sync-reviews`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload reviews
            loadReviews(1);
            showNotification('Avaliações atualizadas com sucesso!', 'success');
        } else {
            showNotification('Erro ao atualizar avaliações: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error syncing reviews:', error);
        showNotification('Erro ao atualizar avaliações', 'error');
    })
    .finally(() => {
        isLoading = false;
        button.textContent = originalText;
        button.disabled = false;
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Review modal functionality
function openReviewModal() {
    document.getElementById('reviewModal').classList.remove('hidden');
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.add('hidden');
}

function setRating(rating) {
    document.getElementById('rating').value = rating;
    
    // Update star display
    const stars = document.querySelectorAll('.star-rating');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
}

// Initialize map if coordinates are available
@if($establishment->latitude && $establishment->longitude)
function initMap() {
    const establishment = {
        lat: {{ $establishment->latitude }},
        lng: {{ $establishment->longitude }}
    };
    
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        center: establishment,
        styles: [
            {
                featureType: "poi",
                elementType: "labels",
                stylers: [{ visibility: "off" }]
            }
        ]
    });
    
    const marker = new google.maps.Marker({
        position: establishment,
        map: map,
        title: "{{ $establishment->name }}",
        icon: {
            url: `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(`
                <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="18" fill="#f97316" stroke="#fff" stroke-width="2"/>
                    <text x="20" y="26" text-anchor="middle" font-size="16" fill="white">📍</text>
                </svg>
            `)}`,
            scaledSize: new google.maps.Size(40, 40),
            anchor: new google.maps.Point(20, 20)
        }
    });
    
    const infoWindow = new google.maps.InfoWindow({
        content: `
            <div class="p-2">
                <h3 class="font-bold text-gray-900">{{ $establishment->name }}</h3>
                <p class="text-sm text-gray-600">{{ $establishment->formatted_address ?? $establishment->address ?? 'Endereço não disponível' }}</p>
            </div>
        `
    });
    
    marker.addListener("click", () => {
        infoWindow.open(map, marker);
    });
}

// Initialize map when page loads
window.initMap = initMap;
@endif
</script>

@if($establishment->latitude && $establishment->longitude)
<!-- Load Google Maps API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap"></script>
@endif
@endsection