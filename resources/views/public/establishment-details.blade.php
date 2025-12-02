@extends('layouts.app')

@section('title', 'Detalhes do Estabelecimento')

@section('content')
<div class="min-h-screen bg-gray-50" data-version="responsive-2025-11-29">
    <!-- Hero Section -->
    <div class="relative w-full h-48 sm:h-64 md:h-80 lg:h-96 bg-gradient-to-r from-churrasco-600 to-churrasco-800 overflow-hidden">
        @if($establishment->photo_urls && count($establishment->photo_urls) > 0)
            <div class="absolute inset-0 w-full h-full">
                <img src="{{ route('establishment.photo.proxy', ['id' => $establishment->id, 'index' => 0]) }}" 
                     alt="{{ $establishment->name }}" 
                     class="w-full h-full object-cover block"
                     style="width: 100% !important; height: 100% !important; object-fit: cover; display: block !important; opacity: 1 !important;"
                     onload="this.style.opacity='1'; this.style.display='block'; this.style.visibility='visible';"
                     onerror="this.style.display='none'; const fallback = this.parentElement.nextElementSibling; if (fallback) fallback.style.display='flex';">
                <div class="absolute inset-0 bg-gradient-to-r from-churrasco-600 to-churrasco-800 flex items-center justify-center" style="display: none;">
                    <div class="text-white text-center px-4">
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-2 sm:mb-4">{{ $establishment->name }}</h1>
                        <p class="text-sm sm:text-base md:text-xl">{{ $establishment->formatted_address ?? $establishment->address ?? 'Endereço não disponível' }}</p>
                    </div>
                </div>
            </div>
        @endif
        <div class="absolute inset-0 bg-black bg-opacity-20 z-10 pointer-events-none"></div>
        <div class="relative w-full max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 h-full flex items-center z-20">
            <div class="text-white w-full">
                <h1 class="text-lg sm:text-xl md:text-2xl lg:text-3xl xl:text-4xl font-bold mb-1 sm:mb-2 md:mb-3 lg:mb-4 break-words">{{ $establishment->name }}</h1>
                <p class="text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl mb-2 sm:mb-3 md:mb-4 lg:mb-6 break-words">{{ $establishment->formatted_address ?? $establishment->address ?? 'Endereço não disponível' }}</p>
                <div class="flex flex-col xs:flex-row items-start xs:items-center space-y-1 xs:space-y-0 xs:space-x-3 sm:space-x-4 md:space-x-6">
                    <div class="flex items-center flex-wrap gap-1">
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3 h-3 xs:w-4 xs:h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 {{ $i <= floor($establishment->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="ml-1 sm:ml-2 text-xs xs:text-sm sm:text-base md:text-lg font-semibold">{{ number_format($establishment->rating ?? 0, 1) }}</span>
                        <span class="ml-1 text-xs text-gray-300">({{ $establishment->user_ratings_total ?? $establishment->review_count ?? 0 }})</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 xs:w-4 xs:h-4 sm:w-5 sm:h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-xs xs:text-sm sm:text-base">{{ $establishment->city ?? 'Porto Alegre' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6 md:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- About Section -->
                <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 mb-6 sm:mb-8">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Sobre o Estabelecimento</h2>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed mb-4 sm:mb-6">
                        {{ $establishment->description ?? 'Este estabelecimento oferece uma experiência autêntica de churrasco em Porto Alegre, com cortes premium de carne e culinária tradicional gaúcha.' }}
                    </p>
                    
                    <!-- Features -->
                    <div class="grid grid-cols-2 gap-3 sm:gap-4">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs sm:text-sm text-gray-700">Estacionamento</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs sm:text-sm text-gray-700">Wi-Fi Gratuito</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs sm:text-sm text-gray-700">Acessibilidade</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs sm:text-sm text-gray-700">Delivery</span>
                        </div>
                    </div>
                </div>

                <!-- Photo Gallery -->
                @if($establishment->photo_urls && count($establishment->photo_urls) > 0)
                <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 mb-8">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4">Galeria de Fotos</h2>
                    <div class="space-y-4 sm:space-y-6">
                        @foreach($establishment->photo_urls as $index => $photoUrl)
                            <div class="relative rounded-xl overflow-hidden shadow-lg bg-gray-100 w-full hover:opacity-90 transition-opacity">
                                <img src="{{ route('establishment.photo.proxy', ['id' => $establishment->id, 'index' => $index]) }}" 
                                     alt="{{ $establishment->name }} - Foto {{ $index + 1 }}" 
                                     class="w-full h-auto object-cover block cursor-pointer"
                                     style="min-height: 200px; max-height: 400px; width: 100%; display: block !important; opacity: 1 !important; background-color: #f3f4f6;"
                                     loading="lazy"
                                     onclick="openPhotoModal({{ $index }})"
                                     onload="this.style.opacity='1'; this.style.display='block'; this.style.visibility='visible'; console.log('Image loaded:', this.src);"
                                     onerror="console.error('Image failed:', this.src); this.style.display='none'; const placeholder = this.nextElementSibling; if (placeholder && placeholder.classList.contains('photo-placeholder')) placeholder.style.display='flex';">
                                
                                <!-- Placeholder if image fails -->
                                <div class="photo-placeholder w-full bg-gradient-to-br from-churrasco-500 to-churrasco-600 flex items-center justify-center absolute inset-0" style="min-height: 250px; display: none;">
                                    <div class="text-center p-4">
                                        <span class="text-white text-4xl sm:text-6xl font-bold block mb-2">{{ substr($establishment->name, 0, 1) }}</span>
                                        <span class="text-white text-xs sm:text-sm opacity-90">Foto {{ $index + 1 }} - Erro ao carregar</span>
                                    </div>
                                </div>
                                
                                <!-- Photo number badge -->
                                <div class="absolute top-2 right-2 sm:top-4 sm:right-4 bg-white/90 backdrop-blur-sm rounded-full px-2 py-1 sm:px-4 sm:py-2 shadow-lg z-10">
                                    <span class="text-xs sm:text-sm font-semibold text-gray-900">{{ $index + 1 }} / {{ count($establishment->photo_urls) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Reviews Section -->
                <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6">
                        <div>
                            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900">Avaliações</h2>
                            <div class="flex items-center mt-2 flex-wrap gap-1 sm:gap-0">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 {{ $i <= floor($establishment->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="ml-1 sm:ml-2 text-xs sm:text-sm md:text-base lg:text-lg font-semibold">{{ number_format($establishment->rating ?? 0, 1) }}</span>
                                <span class="ml-1 text-xs sm:text-sm text-gray-500">({{ $establishment->user_ratings_total ?? $establishment->review_count ?? 0 }} avaliações)</span>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2">
                            @if($establishment->external_id)
                                <button onclick="syncExternalReviews()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm md:text-base font-semibold transition-colors duration-200 whitespace-nowrap">
                                    Atualizar Avaliações
                                </button>
                            @endif
                            @auth
                                <button onclick="openReviewModal()" class="bg-churrasco-500 hover:bg-churrasco-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm md:text-base font-semibold transition-colors duration-200 whitespace-nowrap">
                                    Escrever Avaliação
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="bg-churrasco-500 hover:bg-churrasco-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm md:text-base font-semibold transition-colors duration-200 text-center whitespace-nowrap">
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
                    <div id="reviewsContainer" class="space-y-4 sm:space-y-6">
                        <!-- Reviews will be loaded here via JavaScript -->
                    </div>

                    <!-- Load More Button -->
                    <div class="text-center mt-4 sm:mt-6">
                        <button onclick="loadMoreReviews()" id="loadMoreBtn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 sm:px-6 sm:py-2 rounded-lg text-sm sm:text-base font-semibold transition-colors duration-200">
                            Carregar Mais Avaliações
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-4 sm:space-y-6">
                <!-- Contact Info -->
                <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Informações de Contato</h3>
                    <div class="space-y-2 sm:space-y-3">
                        <div class="flex items-start">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 mr-2 sm:mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-xs sm:text-sm text-gray-700 break-words">{{ $establishment->formatted_address ?? $establishment->address ?? 'Endereço não disponível' }}</span>
                        </div>
                        @if($establishment->formatted_phone_number)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-xs sm:text-sm text-gray-700">{{ $establishment->formatted_phone_number }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Opening Hours -->
                <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Horário de Funcionamento</h3>
                    <div class="space-y-2">
                        @if($establishment->opening_hours)
                            @php
                                $hours = is_string($establishment->opening_hours) ? json_decode($establishment->opening_hours, true) : $establishment->opening_hours;
                            @endphp
                            @if(is_array($hours))
                                @foreach($hours as $day => $time)
                                    <div class="flex justify-between">
                                        <span class="text-xs sm:text-sm text-gray-600">{{ ucfirst($day) }}</span>
                                        <span class="text-xs sm:text-sm text-gray-900 font-medium">{{ is_array($time) ? ($time['open'] ?? '') . ' - ' . ($time['close'] ?? '') : ($time ?? 'Fechado') }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-xs sm:text-sm text-gray-600">{{ $establishment->opening_hours }}</p>
                            @endif
                        @else
                            <p class="text-xs sm:text-sm text-gray-600">Horário de funcionamento não disponível</p>
                        @endif
                    </div>
                </div>

                <!-- Map -->
                <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Localização</h3>
                    @if($establishment->latitude && $establishment->longitude)
                        <div id="map" class="h-40 sm:h-48 md:h-56 rounded-lg"></div>
                    @else
                        <div class="h-40 sm:h-48 md:h-56 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span class="text-xs sm:text-sm text-gray-500 text-center px-2">Coordenadas não disponíveis</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div id="photoModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
        <div class="relative max-w-4xl w-full max-h-full mx-2 sm:mx-0">
            <button onclick="closePhotoModal()" class="absolute top-2 right-2 sm:top-4 sm:right-4 text-white hover:text-gray-300 z-10 bg-black/50 rounded-full p-2">
                <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modalPhoto" src="" alt="" class="max-w-full max-h-[90vh] rounded-lg mx-auto block">
            <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 text-white text-center bg-black/50 rounded-full px-3 py-1 sm:px-4 sm:py-2">
                <p id="photoCounter" class="text-xs sm:text-sm"></p>
            </div>
            @if($establishment->photo_urls && count($establishment->photo_urls) > 1)
            <button onclick="previousPhoto()" class="absolute left-2 sm:left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 bg-black/50 rounded-full p-2 sm:p-3">
                <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button onclick="nextPhoto()" class="absolute right-2 sm:right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 bg-black/50 rounded-full p-2 sm:p-3">
                <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-3 sm:p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-4 sm:p-6 max-h-[90vh] overflow-y-auto mx-2 sm:mx-0">
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
const establishmentId = {{ $establishment->id }};
const photos = @json($establishment->photo_urls ?? []);

function openPhotoModal(index) {
    currentPhotoIndex = index;
    const photoUrl = `/establishment-photo/${establishmentId}/${index}`;
    document.getElementById('modalPhoto').src = photoUrl;
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

// Load reviews on page load and handle images
document.addEventListener('DOMContentLoaded', function() {
    loadReviews();
    
    // Force all images to be visible - find all images in photo gallery
    const allImages = document.querySelectorAll('img[src*="establishment-photo"], .relative img[src*="/establishment-photo/"]');
    allImages.forEach(function(img) {
        img.style.opacity = '1';
        img.style.visibility = 'visible';
        img.style.display = 'block';
        img.style.backgroundColor = '#f3f4f6';
        
        // Make images clickable to open modal
        const parentDiv = img.closest('.relative');
        if (parentDiv && !parentDiv.classList.contains('photo-placeholder')) {
            const srcMatch = img.src.match(/\/establishment-photo\/(\d+)\/(\d+)/);
            if (srcMatch) {
                const index = parseInt(srcMatch[2]);
                parentDiv.style.cursor = 'pointer';
                parentDiv.addEventListener('click', function() {
                    openPhotoModal(index);
                });
            }
        }
        
        img.addEventListener('load', function() {
            this.style.opacity = '1';
            this.style.visibility = 'visible';
            this.style.display = 'block';
            console.log('Image loaded successfully:', this.src);
        });
        
        img.addEventListener('error', function() {
            console.error('Image failed to load:', this.src);
            this.style.display = 'none';
            const placeholder = this.nextElementSibling;
            if (placeholder && placeholder.classList.contains('photo-placeholder')) {
                placeholder.style.display = 'flex';
            }
        });
        
        // Test proxy endpoint
        fetch(img.src)
            .then(response => {
                console.log('Proxy test - Status:', response.status, 'Content-Type:', response.headers.get('content-type'));
                if (!response.ok || !response.headers.get('content-type')?.includes('image')) {
                    console.error('Proxy returned invalid response');
                }
            })
            .catch(error => {
                console.error('Proxy fetch error:', error);
            });
    });
});

function loadReviews(page = 1) {
    if (isLoading) return;
    
    isLoading = true;
    document.getElementById('reviewsLoading').classList.remove('hidden');
    
    fetch(`/api/establishments/{{ $establishment->id }}/reviews?page=${page}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error("Response is not JSON");
            }
            return response.json();
        })
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
                if (data.has_more) {
                    document.getElementById('loadMoreBtn').style.display = 'block';
                } else {
                    document.getElementById('loadMoreBtn').style.display = 'none';
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
    reviewElement.className = 'border-b border-gray-200 pb-4 sm:pb-6 last:border-b-0';
    
    const authorInitials = review.author_name.split(' ').map(name => name.charAt(0)).join('').substring(0, 2).toUpperCase();
    const avatarColor = review.type === 'external' ? 'from-blue-500 to-blue-600' : 'from-churrasco-500 to-churrasco-600';
    
    reviewElement.innerHTML = `
        <div class="flex items-start space-x-2 sm:space-x-4">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br ${avatarColor} rounded-full flex items-center justify-center flex-shrink-0">
                ${review.author_avatar ? 
                    `<img src="${review.author_avatar}" alt="${review.author_name}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover">` : 
                    `<span class="text-white text-xs sm:text-sm font-bold">${authorInitials}</span>`
                }
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 space-y-1 sm:space-y-0">
                    <div class="flex items-center space-x-2 flex-wrap">
                        <h4 class="text-sm sm:text-base font-semibold text-gray-900">${review.author_name}</h4>
                        ${review.type === 'external' ? '<span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">Google</span>' : ''}
                    </div>
                    <div class="flex text-yellow-400">
                        ${Array.from({length: 5}, (_, i) => 
                            `<svg class="w-3 h-3 sm:w-4 sm:h-4 ${i < review.rating ? 'text-yellow-400' : 'text-gray-300'}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>`
                        ).join('')}
                    </div>
                </div>
                <p class="text-xs sm:text-sm text-gray-600 mb-2 break-words">${review.text || 'Sem comentário'}</p>
                <span class="text-xs text-gray-500">${review.time_ago}</span>
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

<style>
/* Force images to be visible */
.relative img {
    opacity: 1 !important;
    visibility: visible !important;
    display: block !important;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .relative.h-48 {
        height: 12rem !important;
    }
}

/* Ensure images load properly */
img[src*="establishment-photo"] {
    background-color: #f3f4f6;
    min-height: 200px;
}
</style>
@endsection