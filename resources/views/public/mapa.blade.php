@extends('layouts.app')

@section('title', 'Mapa Interativo')

@section('content')
<div class="min-h-screen bg-gray-50">
    <header class="bg-white border-b shadow-sm">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-6 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                <div>
                <h1 class="text-3xl font-bold text-gray-900">Encontre os melhores pontos de churrasco</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Explore estabelecimentos do POA Capital do Churrasco, filtre por categoria, avaliação ou comodidades, e visite páginas dinâmicas alimentadas por dados do painel.
                </p>
                </div>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                    <div class="relative">
                    <label for="searchInput" class="sr-only">Buscar estabelecimentos</label>
                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Buscar por nome ou endereço"
                        class="w-72 rounded-lg border border-gray-300 px-4 py-2 pr-10 text-sm text-gray-700 focus:border-churrasco-500 focus:outline-none focus:ring-2 focus:ring-churrasco-500"
                    >
                    <svg class="pointer-events-none absolute right-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                <button id="toggleFilters" class="inline-flex items-center gap-2 rounded-lg bg-churrasco-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-churrasco-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filtros
                    </button>
                </div>
            </div>
    </header>

    <div class="flex h-[calc(100vh-200px)]">
        <section class="relative flex-1">
            <div id="map" class="h-full w-full"></div>
            <div class="absolute top-4 right-4 flex flex-col gap-2">
                <button id="locateBtn" class="rounded-lg border border-gray-200 bg-white p-2 shadow transition hover:bg-gray-100" title="Localizar-me">
                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
                <button id="fullscreenBtn" class="rounded-lg border border-gray-200 bg-white p-2 shadow transition hover:bg-gray-100" title="Tela cheia">
                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4h4M4 4l5 5m11-1V4h-4m4 0l-5 5M4 16v4h4m-4 0l5-5m11 5v-4h-4m4 4l-5-5" />
                    </svg>
                </button>
            </div>
        </section>

        <aside class="flex w-96 flex-col border-l border-gray-200 bg-white">
            <div id="filterPanel" class="space-y-6 border-b border-gray-200 p-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Filtrar resultados</h2>
                    <p class="text-sm text-gray-500">Ajuste o mapa às suas preferências e encontre os estabelecimentos certos.</p>
        </div>
                <div>
                    <label for="categoryFilter" class="block text-sm font-medium text-gray-700">Categoria</label>
                    <select id="categoryFilter" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-churrasco-500 focus:outline-none focus:ring-2 focus:ring-churrasco-500">
                        <option value="">Todas as categorias</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="priceFilter" class="block text-sm font-medium text-gray-700">Faixa de preço</label>
                    <select id="priceFilter" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-churrasco-500 focus:outline-none focus:ring-2 focus:ring-churrasco-500">
                        <option value="">Qualquer preço</option>
                        <option value="1">$ · Econômico</option>
                        <option value="2">$$ · Casual</option>
                        <option value="3">$$$ · Premium</option>
                        <option value="4">$$$$ · Exclusivo</option>
                    </select>
                </div>
                <div>
                    <label for="ratingFilter" class="block text-sm font-medium text-gray-700">Avaliação mínima</label>
                    <select id="ratingFilter" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-churrasco-500 focus:outline-none focus:ring-2 focus:ring-churrasco-500">
                        <option value="">Qualquer avaliação</option>
                        <option value="4">4.0 e acima</option>
                        <option value="3">3.0 e acima</option>
                        <option value="2">2.0 e acima</option>
                        <option value="1">1.0 e acima</option>
                    </select>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-700">Comodidades</h3>
                    <div class="mt-3 grid grid-cols-2 gap-2 text-sm text-gray-600">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" value="estacionamento" class="amenity-checkbox rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                            Estacionamento
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" value="wifi" class="amenity-checkbox rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                            Wi-Fi
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" value="delivery" class="amenity-checkbox rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                            Delivery
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" value="acessibilidade" class="amenity-checkbox rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                            Acessibilidade
                        </label>
                    </div>
                </div>
                <button id="applyFilters" class="w-full rounded-lg bg-churrasco-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-churrasco-600">
                    Aplicar filtros
                </button>
            </div>

            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Resultados</h2>
                <span id="resultsCount" class="text-sm text-gray-600">0 estabelecimentos</span>
            </div>

            <div class="flex flex-1 flex-col overflow-hidden">
                <div id="establishmentsList" class="flex-1 space-y-4 overflow-y-auto px-6 py-6">
                    <div class="rounded-lg border border-dashed border-gray-200 bg-gray-50 p-6 text-center text-sm text-gray-500">
                        Ajuste os filtros ou busque para carregar estabelecimentos.
                    </div>
                </div>
                
                <!-- Pagination Controls -->
                <div id="paginationControls" class="hidden border-t border-gray-200 bg-white px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <span id="paginationInfo">Mostrando 0-0 de 0</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button id="prevPage" class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50" disabled>
                                Anterior
                            </button>
                            <div id="pageNumbers" class="flex items-center gap-1 text-sm text-gray-600"></div>
                            <button id="nextPage" class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50" disabled>
                                Próxima
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>

<script>
    const mapState = {
        map: null,
        markers: [],
        infoWindow: null,
        establishments: [],
        filters: {
            search: '',
            category: '',
            priceLevel: '',
            ratingMin: '',
            amenities: [],
        },
        pagination: {
            currentPage: 1,
            itemsPerPage: 10,
        },
    };

    const amenitiesTranslation = {
        'estacionamento': 'estacionamento',
        'wifi': 'wifi',
        'delivery': 'delivery',
        'acessibilidade': 'acessibilidade',
    };

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const priceFilter = document.getElementById('priceFilter');
        const ratingFilter = document.getElementById('ratingFilter');
        const amenityCheckboxes = document.querySelectorAll('.amenity-checkbox');
        const applyFiltersButton = document.getElementById('applyFilters');
        const toggleFiltersButton = document.getElementById('toggleFilters');
        const filterPanel = document.getElementById('filterPanel');

        searchInput.addEventListener('input', debounce((event) => {
            mapState.filters.search = event.target.value;
            fetchEstablishments();
        }, 400));

        categoryFilter.addEventListener('change', (event) => {
            mapState.filters.category = event.target.value;
        });

        priceFilter.addEventListener('change', (event) => {
            mapState.filters.priceLevel = event.target.value;
        });

        ratingFilter.addEventListener('change', (event) => {
            mapState.filters.ratingMin = event.target.value;
        });

        amenityCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                const selected = Array.from(amenityCheckboxes)
                    .filter((item) => item.checked)
                    .map((item) => amenitiesTranslation[item.value] ?? item.value);
                mapState.filters.amenities = selected;
            });
        });

        applyFiltersButton.addEventListener('click', () => {
            mapState.pagination.currentPage = 1; // Reset to first page when filters change
            fetchEstablishments();
        });

        // Pagination button handlers
        document.getElementById('prevPage').addEventListener('click', () => {
            if (mapState.pagination.currentPage > 1) {
                goToPage(mapState.pagination.currentPage - 1);
            }
        });

        document.getElementById('nextPage').addEventListener('click', () => {
            const totalPages = Math.ceil(mapState.establishments.length / mapState.pagination.itemsPerPage);
            if (mapState.pagination.currentPage < totalPages) {
                goToPage(mapState.pagination.currentPage + 1);
            }
        });

        toggleFiltersButton.addEventListener('click', () => {
            filterPanel.classList.toggle('hidden');
        });

        document.getElementById('locateBtn').addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    if (mapState.map) {
                        mapState.map.setCenter(userLocation);
                        mapState.map.setZoom(15);
                    }
                });
            }
        });

        document.getElementById('fullscreenBtn').addEventListener('click', () => {
            const mapContainer = document.getElementById('map');
            if (!document.fullscreenElement) {
                mapContainer.requestFullscreen().catch(() => {});
            } else {
                document.exitFullscreen().catch(() => {});
            }
        });
    });

    window.initMap = function initMap() {
        const portoAlegre = { lat: -30.0346, lng: -51.2177 };

        mapState.map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: portoAlegre,
            styles: [{ featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] }],
        });

        mapState.infoWindow = new google.maps.InfoWindow();
        fetchEstablishments();
    };

    async function fetchEstablishments() {
        const params = new URLSearchParams();
        
        // Include external establishments based on system settings
        params.append('include_external', 'true');

        if (mapState.filters.search) {
            params.append('search', mapState.filters.search);
        }
        if (mapState.filters.category) {
            params.append('category', mapState.filters.category);
        }
        if (mapState.filters.priceLevel) {
            params.append('price_level', mapState.filters.priceLevel);
        }
        if (mapState.filters.ratingMin) {
            params.append('rating_min', mapState.filters.ratingMin);
        }
        if (mapState.filters.amenities.length > 0) {
            mapState.filters.amenities.forEach((amenity) => params.append('amenities[]', amenity));
        }

        try {
            const response = await fetch(`/api/establishments/map/data?${params.toString()}`);
        const data = await response.json();
        
        if (data.success) {
                mapState.establishments = data.establishments || [];
                updateMarkers();
                updateList();
        } else {
                console.error('Erro ao carregar estabelecimentos:', data.error || 'Erro desconhecido');
                mapState.establishments = [];
                updateMarkers();
                updateList();
            }
        } catch (error) {
            console.error('Falha ao carregar estabelecimentos:', error);
            mapState.establishments = [];
            updateMarkers();
            updateList();
        }
    }

    function updateMarkers() {
        mapState.markers.forEach((marker) => marker.setMap(null));
        mapState.markers = [];

        mapState.establishments.forEach((establishment) => {
            if (!establishment.latitude || !establishment.longitude) {
                return;
            }

            const lat = parseFloat(establishment.latitude);
            const lng = parseFloat(establishment.longitude);

            if (isNaN(lat) || isNaN(lng)) {
                return;
            }
            
            const marker = new google.maps.Marker({
                position: { 
                    lat: lat,
                    lng: lng,
                },
                map: mapState.map,
                title: establishment.name,
                icon: buildMarkerIcon(establishment.category),
            });

            marker.addListener('click', () => {
                mapState.infoWindow.setContent(buildInfoWindow(establishment));
                mapState.infoWindow.open(mapState.map, marker);
            });

            mapState.markers.push(marker);
        });
    }

    function updateList() {
        const listContainer = document.getElementById('establishmentsList');
        const countLabel = document.getElementById('resultsCount');
        const paginationControls = document.getElementById('paginationControls');
        const totalItems = mapState.establishments.length;

        countLabel.textContent = `${totalItems} estabelecimento${totalItems === 1 ? '' : 's'}`;

        if (totalItems === 0) {
            listContainer.innerHTML = `
                <div class="rounded-lg border border-dashed border-gray-200 bg-gray-50 p-6 text-center text-sm text-gray-500">
                    Não encontramos estabelecimentos com os filtros atuais.
                </div>
            `;
            paginationControls.classList.add('hidden');
            return;
        }

        // Reset to first page if current page is out of bounds
        const totalPages = Math.ceil(totalItems / mapState.pagination.itemsPerPage);
        if (mapState.pagination.currentPage > totalPages) {
            mapState.pagination.currentPage = 1;
        }

        // Calculate pagination
        const startIndex = (mapState.pagination.currentPage - 1) * mapState.pagination.itemsPerPage;
        const endIndex = Math.min(startIndex + mapState.pagination.itemsPerPage, totalItems);
        const paginatedEstablishments = mapState.establishments.slice(startIndex, endIndex);

        // Clear and render paginated items
        listContainer.innerHTML = '';

        paginatedEstablishments.forEach((establishment) => {
            const wrapper = document.createElement('article');
            wrapper.className = 'cursor-pointer rounded-xl border border-gray-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow';
            wrapper.innerHTML = buildListItem(establishment);
            wrapper.addEventListener('click', () => {
                const marker = mapState.markers.find((item) => item.getTitle() === establishment.name);
                if (marker) {
                    mapState.map.panTo(marker.getPosition());
                    mapState.map.setZoom(15);
                    mapState.infoWindow.setContent(buildInfoWindow(establishment));
                    mapState.infoWindow.open(mapState.map, marker);
                }
            });
            listContainer.appendChild(wrapper);
        });

        // Update pagination controls
        updatePaginationControls(totalItems, totalPages);
    }

    function updatePaginationControls(totalItems, totalPages) {
        const paginationControls = document.getElementById('paginationControls');
        const paginationInfo = document.getElementById('paginationInfo');
        const pageNumbers = document.getElementById('pageNumbers');
        const prevButton = document.getElementById('prevPage');
        const nextButton = document.getElementById('nextPage');

        if (totalPages <= 1) {
            paginationControls.classList.add('hidden');
            return;
        }

        paginationControls.classList.remove('hidden');

        // Update pagination info
        const startIndex = (mapState.pagination.currentPage - 1) * mapState.pagination.itemsPerPage + 1;
        const endIndex = Math.min(startIndex + mapState.pagination.itemsPerPage - 1, totalItems);
        paginationInfo.textContent = `Mostrando ${startIndex}-${endIndex} de ${totalItems}`;

        // Update page numbers
        const currentPage = mapState.pagination.currentPage;
        let pageNumbersHTML = '';
        
        // Show page numbers (max 5 pages visible)
        const maxVisible = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
        let endPage = Math.min(totalPages, startPage + maxVisible - 1);
        
        // Adjust start page if we're near the end
        if (endPage - startPage < maxVisible - 1) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }

        // Show first page and ellipsis if needed
        if (startPage > 1) {
            pageNumbersHTML += `<button class="px-2 py-1 text-sm text-gray-600 hover:text-gray-900 rounded" onclick="goToPage(1)">1</button>`;
            if (startPage > 2) {
                pageNumbersHTML += `<span class="px-2 text-sm text-gray-400">...</span>`;
            }
        }

        // Show page numbers in range
        for (let i = startPage; i <= endPage; i++) {
            if (i === currentPage) {
                pageNumbersHTML += `<span class="px-2 py-1 text-sm font-semibold text-churrasco-600 bg-churrasco-50 rounded">${i}</span>`;
            } else {
                pageNumbersHTML += `<button class="px-2 py-1 text-sm text-gray-600 hover:text-gray-900 rounded" onclick="goToPage(${i})">${i}</button>`;
            }
        }

        // Show last page and ellipsis if needed
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                pageNumbersHTML += `<span class="px-2 text-sm text-gray-400">...</span>`;
            }
            pageNumbersHTML += `<button class="px-2 py-1 text-sm text-gray-600 hover:text-gray-900 rounded" onclick="goToPage(${totalPages})">${totalPages}</button>`;
        }

        // Also show "Página X de Y" text
        pageNumbers.innerHTML = `<span class="text-xs text-gray-500 mr-2">Página ${currentPage} de ${totalPages}</span>` + pageNumbersHTML;

        // Update button states
        prevButton.disabled = currentPage === 1;
        nextButton.disabled = currentPage === totalPages;
    }

    function goToPage(page) {
        const totalPages = Math.ceil(mapState.establishments.length / mapState.pagination.itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            mapState.pagination.currentPage = page;
            updateList();
            // Scroll to top of list
            document.getElementById('establishmentsList').scrollTop = 0;
        }
    }

    function buildMarkerIcon(category) {
        const emojiMap = {
        churrascaria: '🔥',
            'açougue': '🥩',
        supermercado: '🛒',
        restaurante: '🍽️',
        bar: '🍺',
            lanchonete: '🍔',
    };

        const emoji = emojiMap[category] || '📍';
    
    return {
        url: `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(`
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="18" fill="#f97316" stroke="#fff" stroke-width="2" />
                    <text x="20" y="26" text-anchor="middle" font-size="16" fill="white">${emoji}</text>
            </svg>
        `)}`,
        scaledSize: new google.maps.Size(40, 40),
            anchor: new google.maps.Point(20, 20),
        };
    }

    function buildInfoWindow(establishment) {
        const rating = parseFloat(establishment.rating ?? 0).toFixed(1);
        const totalReviews = establishment.user_ratings_total ?? establishment.review_count ?? 0;
        const address = establishment.formatted_address ?? establishment.address ?? 'Endereço não disponível';
        const slug = establishment.slug ?? establishment.id;

        return `
            <div class="max-w-sm p-2">
                <h3 class="text-lg font-semibold text-gray-900">${establishment.name}</h3>
                <p class="mt-1 text-sm text-gray-600">${address}</p>
                <div class="mt-3 flex items-center gap-3 text-sm text-gray-600">
                    <span class="flex items-center gap-1 text-orange-500">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        ${rating}
                    </span>
                    <span>${totalReviews} avaliações</span>
                </div>
                <div class="mt-4 flex gap-2">
                    <a href="/establishments/${slug}" class="inline-flex flex-1 items-center justify-center rounded-lg bg-churrasco-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-churrasco-600">Ver detalhes</a>
                    <button type="button" class="inline-flex items-center justify-center rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50" onclick="toggleFavorite(${establishment.id})">❤</button>
            </div>
        </div>
    `;
    }

    function buildListItem(establishment) {
        const rating = parseFloat(establishment.rating ?? 0).toFixed(1);
        const totalReviews = establishment.user_ratings_total ?? establishment.review_count ?? 0;
        const address = establishment.formatted_address ?? establishment.address ?? 'Endereço não disponível';
        const status = establishment.business_status && establishment.business_status !== 'OPERATIONAL'
            ? `<span class="text-xs font-medium uppercase text-red-500">${establishment.business_status}</span>`
            : '';
        
        // Get first photo URL if available
        let photoHtml = '';
        if (establishment.photo_urls && Array.isArray(establishment.photo_urls) && establishment.photo_urls.length > 0) {
            const photoUrl = establishment.photo_urls[0];
            photoHtml = `<img src="${photoUrl}" alt="${establishment.name}" class="h-16 w-16 flex-shrink-0 rounded-lg object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />`;
        }
        
        const emojiFallback = `<div class="flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-lg bg-orange-100 text-2xl" style="display: ${photoHtml ? 'none' : 'flex'};">${buildListEmoji(establishment.category)}</div>`;

        return `
            <div class="flex items-start gap-3">
                ${photoHtml || ''}
                ${emojiFallback}
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-900">${establishment.name}</h3>
                        ${status}
                    </div>
                    <p class="mt-1 text-xs text-gray-500">${address}</p>
                    <div class="mt-2 flex items-center gap-3 text-xs text-gray-500">
                        <span class="flex items-center gap-1 text-orange-500">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            ${rating}
                        </span>
                        <span>${totalReviews} avaliações</span>
                        <span class="capitalize">${establishment.category ?? 'categoria n/d'}</span>
                </div>
                </div>
            </div>
        `;
    }

    function buildListEmoji(category) {
        const emojiMap = {
            churrascaria: '🔥',
            'açougue': '🥩',
            supermercado: '🛒',
            restaurante: '🍽️',
            bar: '🍺',
            lanchonete: '🍔',
        };

        return emojiMap[category] || '📍';
    }

    function debounce(callback, delay) {
        let timer;
        return function debounced(...args) {
            clearTimeout(timer);
            timer = setTimeout(() => callback.apply(this, args), delay);
        };
    }

    async function toggleFavorite(establishmentId) {
        try {
            const response = await fetch('{{ route('favorites.toggle') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ establishment_id: establishmentId }),
            });

            if (!response.ok) {
                throw new Error('Request failed');
            }

            const data = await response.json();
            showToast(data.message || 'Favorito atualizado.');
        } catch (error) {
            showToast('Você precisa fazer login para gerenciar favoritos.', true);
        }
    }

    function showToast(message, isError = false) {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 rounded-lg px-4 py-2 text-sm font-semibold shadow-lg ${isError ? 'bg-red-500 text-white' : 'bg-churrasco-500 text-white'}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
</script>

@if(config('services.google.maps_api_key'))
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap"></script>
@else
<div class="bg-red-50 px-6 py-4 text-red-700">
    Chave da API do Google Maps não configurada. Por favor, revise as variáveis de ambiente.
</div>
@endif
@endsection
