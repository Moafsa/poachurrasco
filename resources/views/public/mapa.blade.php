@extends('layouts.app')

@section('title', 'Mapa de Estabelecimentos')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Mapa de Estabelecimentos</h1>
                    <p class="text-gray-600 mt-2">Descubra os melhores lugares para churrasco em Porto Alegre</p>
                </div>
                <div class="flex space-x-4">
                    <!-- Search Box -->
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Buscar estabelecimentos..." 
                               class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                        <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    
                    <!-- Filter Button -->
                    <button id="filterBtn" class="bg-churrasco-500 hover:bg-churrasco-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filtros
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex h-screen">
        <!-- Map Container -->
        <div class="flex-1 relative">
            <div id="map" class="w-full h-full"></div>
            
            <!-- Map Controls -->
            <div class="absolute top-4 right-4 space-y-2">
                <button id="locateBtn" class="bg-white hover:bg-gray-50 text-gray-700 px-3 py-2 rounded-lg shadow-lg border border-gray-200 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </button>
                <button id="fullscreenBtn" class="bg-white hover:bg-gray-50 text-gray-700 px-3 py-2 rounded-lg shadow-lg border border-gray-200 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="w-96 bg-white shadow-lg overflow-y-auto">
            <!-- Filter Panel -->
            <div id="filterPanel" class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtros</h3>
                
                <!-- Category Filter -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                    <select id="categoryFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                        <option value="">Todas as categorias</option>
                        <option value="churrascaria">Churrascarias</option>
                        <option value="açougue">Açougues</option>
                        <option value="supermercado">Supermercados</option>
                        <option value="restaurante">Restaurantes</option>
                        <option value="bar">Bares</option>
                        <option value="lanchonete">Lanchonetes</option>
                    </select>
                </div>

                <!-- Price Range Filter -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Faixa de Preço</label>
                    <select id="priceFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                        <option value="">Todas as faixas</option>
                        <option value="$">$ - Econômico</option>
                        <option value="$$">$$ - Moderado</option>
                        <option value="$$$">$$$ - Caro</option>
                        <option value="$$$$">$$$$ - Muito Caro</option>
                    </select>
                </div>

                <!-- Rating Filter -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Avaliação Mínima</label>
                    <select id="ratingFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                        <option value="">Qualquer avaliação</option>
                        <option value="4">4+ estrelas</option>
                        <option value="3">3+ estrelas</option>
                        <option value="2">2+ estrelas</option>
                        <option value="1">1+ estrelas</option>
                    </select>
                </div>

                <!-- Amenities Filter -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comodidades</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" class="amenity-filter" value="estacionamento" class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                            <span class="ml-2 text-sm text-gray-700">Estacionamento</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="amenity-filter" value="wifi" class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                            <span class="ml-2 text-sm text-gray-700">Wi-Fi</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="amenity-filter" value="delivery" class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                            <span class="ml-2 text-sm text-gray-700">Delivery</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="amenity-filter" value="acessibilidade" class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                            <span class="ml-2 text-sm text-gray-700">Acessibilidade</span>
                        </label>
                    </div>
                </div>

                <button id="applyFilters" class="w-full bg-churrasco-500 hover:bg-churrasco-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors duration-200">
                    Aplicar Filtros
                </button>
            </div>

            <!-- Results List -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Estabelecimentos</h3>
                <div id="establishmentsList" class="space-y-4">
                    <!-- Results will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Google Maps Script -->
<script>
let map;
let markers = [];
let establishments = [];
let allEstablishments = []; // Store all establishments for pagination
let currentPage = 1;
let establishmentsPerPage = 10;
let infoWindow;

// Sample establishments data (in production, this would come from the backend)
const sampleEstablishments = [
    {
        id: 1,
        name: "Churrascaria Gaúcha",
        address: "Rua da Praia, 123 - Centro",
        latitude: -30.0346,
        longitude: -51.2177,
        category: "churrascaria",
        price_range: "$$",
        rating: 4.2,
        review_count: 127,
        amenities: ["estacionamento", "wifi", "delivery"],
        phone: "(51) 3222-1234",
        website: "https://churrascariagaucha.com"
    },
    {
        id: 2,
        name: "Açougue Central",
        address: "Av. Borges de Medeiros, 456 - Centro Histórico",
        latitude: -30.0311,
        longitude: -51.2305,
        category: "açougue",
        price_range: "$",
        rating: 4.5,
        review_count: 89,
        amenities: ["estacionamento"],
        phone: "(51) 3222-5678"
    },
    {
        id: 3,
        name: "Supermercado do Churrasco",
        address: "Rua dos Andradas, 789 - Cidade Baixa",
        latitude: -30.0408,
        longitude: -51.2189,
        category: "supermercado",
        price_range: "$$",
        rating: 4.0,
        review_count: 203,
        amenities: ["estacionamento", "wifi", "delivery", "acessibilidade"],
        phone: "(51) 3222-9012"
    },
    {
        id: 4,
        name: "Bar do Gaúcho",
        address: "Rua João Pessoa, 321 - Bom Fim",
        latitude: -30.0259,
        longitude: -51.2104,
        category: "bar",
        price_range: "$$",
        rating: 4.3,
        review_count: 156,
        amenities: ["wifi"],
        phone: "(51) 3222-3456"
    },
    {
        id: 5,
        name: "Restaurante Tradicional",
        address: "Av. Independência, 654 - Independência",
        latitude: -30.0275,
        longitude: -51.2289,
        category: "restaurante",
        price_range: "$$$",
        rating: 4.7,
        review_count: 98,
        amenities: ["estacionamento", "wifi", "acessibilidade"],
        phone: "(51) 3222-7890"
    }
];

async function initMap() {
    // Porto Alegre coordinates
    const portoAlegre = { lat: -30.0346, lng: -51.2177 };
    
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13,
        center: portoAlegre,
        styles: [
            {
                featureType: "poi",
                elementType: "labels",
                stylers: [{ visibility: "off" }]
            }
        ]
    });

    infoWindow = new google.maps.InfoWindow();
    
    // Add event listeners
    setupEventListeners();
    
    // Load establishments
    await loadEstablishments();
}

async function loadEstablishments() {
    try {
        // Fetch real establishments from backend
        const response = await fetch('/api/establishments/map/data?include_external=true');
        const data = await response.json();
        
        if (data.success) {
            allEstablishments = data.establishments;
            establishments = data.establishments;
            console.log(`Loaded ${data.count} establishments from backend`);
        } else {
            console.error('Failed to load establishments:', data.error);
            // Fallback to sample data
            allEstablishments = sampleEstablishments;
            establishments = sampleEstablishments;
        }
    } catch (error) {
        console.error('Error loading establishments:', error);
        // Fallback to sample data
        establishments = sampleEstablishments;
    }
    
    displayEstablishments();
}

function displayEstablishments() {
    // Clear existing markers
    markers.forEach(marker => marker.setMap(null));
    markers = [];
    
    // Add markers for each establishment
    establishments.forEach(establishment => {
        // Only add markers if coordinates are valid numbers
        if (establishment.latitude && establishment.longitude && 
            !isNaN(parseFloat(establishment.latitude)) && 
            !isNaN(parseFloat(establishment.longitude))) {
            
            const marker = new google.maps.Marker({
                position: { 
                    lat: parseFloat(establishment.latitude), 
                    lng: parseFloat(establishment.longitude) 
                },
                map: map,
                title: establishment.name,
                icon: getMarkerIcon(establishment.category)
            });
            
            marker.addListener("click", () => {
                showEstablishmentInfo(establishment, marker);
            });
            
            markers.push(marker);
        } else {
            console.warn(`Skipping marker for ${establishment.name} - invalid coordinates:`, {
                lat: establishment.latitude, 
                lng: establishment.longitude
            });
        }
    });
    
    // Update sidebar list
    updateEstablishmentsList();
}

function getMarkerIcon(category) {
    const icons = {
        churrascaria: '🔥',
        açougue: '🥩',
        supermercado: '🛒',
        restaurante: '🍽️',
        bar: '🍺',
        lanchonete: '🍔'
    };
    
    return {
        url: `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(`
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                <circle cx="20" cy="20" r="18" fill="#f97316" stroke="#fff" stroke-width="2"/>
                <text x="20" y="26" text-anchor="middle" font-size="16" fill="white">${icons[category] || '📍'}</text>
            </svg>
        `)}`,
        scaledSize: new google.maps.Size(40, 40),
        anchor: new google.maps.Point(20, 20)
    };
}

function showEstablishmentInfo(establishment, marker) {
    const content = `
        <div class="p-4 max-w-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-2">${establishment.name}</h3>
            ${establishment.photo_urls && establishment.photo_urls.length > 0 ? 
                `<img src="${establishment.photo_urls[0]}" alt="${establishment.name}" class="w-full h-32 object-cover rounded-lg mb-2">` : ''
            }
            <p class="text-gray-600 text-sm mb-2">${(establishment.formatted_address && establishment.formatted_address !== 'null') ? establishment.formatted_address : (establishment.address && establishment.address !== 'null') ? establishment.address : 'Endereço não disponível'}</p>
            <div class="flex items-center mb-2">
                <div class="flex text-yellow-400 mr-2">
                    ${Array(5).fill(0).map((_, i) => 
                        `<svg class="w-4 h-4 ${i < Math.floor(establishment.rating || 0) ? 'text-yellow-400' : 'text-gray-300'}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>`
                    ).join('')}
                </div>
                <span class="text-sm text-gray-600">${establishment.rating || 0} ${establishment.user_ratings_total ? `(${establishment.user_ratings_total})` : ''}</span>
            </div>
            <div class="flex space-x-2">
                <a href="/estabelecimento/${establishment.id}" class="bg-churrasco-500 hover:bg-churrasco-600 text-white px-3 py-1 rounded text-sm font-semibold transition-colors duration-200">
                    Ver Detalhes
                </a>
                <button onclick="addToFavorites(${establishment.id})" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded text-sm font-semibold transition-colors duration-200">
                    ❤️ Favoritar
                </button>
            </div>
        </div>
    `;
    
    infoWindow.setContent(content);
    infoWindow.open(map, marker);
}

function updateEstablishmentsList() {
    const listContainer = document.getElementById('establishmentsList');
    listContainer.innerHTML = '';
    
    // Calculate pagination
    const startIndex = (currentPage - 1) * establishmentsPerPage;
    const endIndex = startIndex + establishmentsPerPage;
    const paginatedEstablishments = establishments.slice(startIndex, endIndex);
    
    // Display establishments for current page
    paginatedEstablishments.forEach(establishment => {
        const establishmentElement = document.createElement('div');
        establishmentElement.className = 'p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow duration-200 cursor-pointer';
        establishmentElement.innerHTML = `
            <div class="flex space-x-3">
                <div class="flex-shrink-0">
                    ${establishment.photo_urls && establishment.photo_urls.length > 0 ? 
                        `<img src="${establishment.photo_urls[0]}" alt="${establishment.name}" class="w-16 h-16 object-cover rounded-lg">` :
                        `<div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>`
                    }
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-semibold text-gray-900 mb-1">${establishment.name}</h4>
                    <p class="text-sm text-gray-600 mb-2">${(establishment.formatted_address && establishment.formatted_address !== 'null') ? establishment.formatted_address : (establishment.address && establishment.address !== 'null') ? establishment.address : 'Endereço não disponível'}</p>
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center">
                    <div class="flex text-yellow-400 mr-2">
                        ${Array(5).fill(0).map((_, i) => 
                            `<svg class="w-3 h-3 ${i < Math.floor(establishment.rating || 0) ? 'text-yellow-400' : 'text-gray-300'}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>`
                        ).join('')}
                    </div>
                    <span class="text-sm text-gray-600">${establishment.rating || 0}</span>
                    ${establishment.user_ratings_total ? `<span class="text-xs text-gray-500 ml-1">(${establishment.user_ratings_total})</span>` : ''}
                </div>
                <div class="flex items-center space-x-2">
                    ${establishment.price_level ? `<span class="text-sm text-gray-500">${'$'.repeat(establishment.price_level)}</span>` : ''}
                    ${establishment.is_external ? '<span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">API</span>' : ''}
                </div>
                </div>
            </div>
            ${establishment.formatted_phone_number ? `<p class="text-xs text-gray-500 mb-1">📞 ${establishment.formatted_phone_number}</p>` : ''}
            ${establishment.business_status && establishment.business_status !== 'OPERATIONAL' ? `<p class="text-xs text-red-500">⚠️ ${establishment.business_status}</p>` : ''}
        `;
        
        establishmentElement.addEventListener('click', () => {
            const marker = markers.find(m => m.title === establishment.name);
            if (marker) {
                map.panTo(marker.getPosition());
                showEstablishmentInfo(establishment, marker);
            }
        });
        
        listContainer.appendChild(establishmentElement);
    });
    
    // Add pagination controls
    addPaginationControls();
}

function addPaginationControls() {
    const listContainer = document.getElementById('establishmentsList');
    const totalPages = Math.ceil(establishments.length / establishmentsPerPage);
    
    if (totalPages <= 1) return;
    
    const paginationDiv = document.createElement('div');
    paginationDiv.className = 'mt-4 flex justify-center items-center space-x-2';
    
    // Previous button
    const prevButton = document.createElement('button');
    prevButton.className = `px-3 py-1 rounded ${currentPage === 1 ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-churrasco-500 text-white hover:bg-churrasco-600'}`;
    prevButton.textContent = 'Anterior';
    prevButton.disabled = currentPage === 1;
    prevButton.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            updateEstablishmentsList();
        }
    });
    
    // Page numbers
    const pageInfo = document.createElement('span');
    pageInfo.className = 'text-sm text-gray-600';
    pageInfo.textContent = `Página ${currentPage} de ${totalPages}`;
    
    // Next button
    const nextButton = document.createElement('button');
    nextButton.className = `px-3 py-1 rounded ${currentPage === totalPages ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-churrasco-500 text-white hover:bg-churrasco-600'}`;
    nextButton.textContent = 'Próximo';
    nextButton.disabled = currentPage === totalPages;
    nextButton.addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            updateEstablishmentsList();
        }
    });
    
    paginationDiv.appendChild(prevButton);
    paginationDiv.appendChild(pageInfo);
    paginationDiv.appendChild(nextButton);
    
    listContainer.appendChild(paginationDiv);
}

function setupEventListeners() {
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        if (query === '') {
            // Reset to all establishments
            establishments = allEstablishments;
            currentPage = 1;
            displayEstablishments();
        } else {
            const filtered = allEstablishments.filter(est => 
                est.name.toLowerCase().includes(query) || 
                (est.address && est.address.toLowerCase().includes(query)) ||
                (est.formatted_address && est.formatted_address.toLowerCase().includes(query))
            );
            establishments = filtered;
            currentPage = 1;
            displayEstablishments();
        }
    });
    
    // Filter functionality
    document.getElementById('applyFilters').addEventListener('click', () => {
        applyFilters();
    });
    
    // Locate button
    document.getElementById('locateBtn').addEventListener('click', () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                map.setCenter(userLocation);
                map.setZoom(15);
            });
        }
    });
}

function applyFilters() {
    const category = document.getElementById('categoryFilter').value;
    const priceRange = document.getElementById('priceFilter').value;
    const minRating = document.getElementById('ratingFilter').value;
    const amenities = Array.from(document.querySelectorAll('.amenity-filter:checked')).map(cb => cb.value);
    
    let filtered = allEstablishments;
    
    if (category) {
        filtered = filtered.filter(est => est.category === category);
    }
    
    if (priceRange) {
        filtered = filtered.filter(est => est.price_range === priceRange);
    }
    
    if (minRating) {
        filtered = filtered.filter(est => est.rating >= parseFloat(minRating));
    }
    
    if (amenities.length > 0) {
        filtered = filtered.filter(est => 
            amenities.every(amenity => est.amenities.includes(amenity))
        );
    }
    
    establishments = filtered;
    currentPage = 1;
    displayEstablishments();
}

function addToFavorites(establishmentId) {
    // This would make an AJAX call to the backend
    console.log('Adding to favorites:', establishmentId);
    // For now, just show an alert
    alert('Funcionalidade de favoritos será implementada!');
}

// Initialize map when page loads
window.initMap = initMap;
</script>

<!-- Load Google Maps API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap"></script>
@endsection