@extends('layouts.app')

@section('title', 'Novo Estabelecimento')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-white">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-black text-gray-900">Novo Estabelecimento</h1>
                    <p class="text-gray-600 mt-2">Cadastre seu estabelecimento na plataforma</p>
                </div>
                <a href="{{ route('establishments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="{{ route('establishments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="establishment-form">
            @csrf
            
            <!-- Link to Existing Establishment -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Vincular-se a um Estabelecimento Existente</h2>
                <p class="text-sm text-gray-600 mb-6">Procure um estabelecimento já cadastrado no sistema para se vincular, ou deixe em branco para criar um novo.</p>
                
                <div class="relative">
                    <label for="establishment_search" class="block text-sm font-semibold text-gray-700 mb-2">
                        Buscar Estabelecimento
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="establishment_search" 
                               name="establishment_search"
                               autocomplete="off"
                               class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-xl focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500 transition-colors duration-200"
                               placeholder="Digite o nome do estabelecimento...">
                        <button type="button" 
                                id="clear_search" 
                                class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                                title="Limpar seleção">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <input type="hidden" id="existing_establishment_id" name="existing_establishment_id" value="">
                        <div id="establishment_search_results" class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-xl shadow-lg max-h-60 overflow-y-auto"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Comece a digitar para ver os estabelecimentos disponíveis</p>
                    <div id="selected_establishment_info" class="hidden mt-3 p-3 bg-churrasco-50 border border-churrasco-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-900" id="selected_name"></p>
                                <p class="text-xs text-gray-600 mt-1" id="selected_address"></p>
                            </div>
                            <span class="text-xs text-churrasco-600 font-semibold">Selecionado</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">OU</span>
                </div>
            </div>

            <!-- Create New Establishment -->
            <div id="new-establishment-form">
            <!-- Basic Information -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Informações Básicas</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nome do Estabelecimento *
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500 transition-colors duration-200"
                               placeholder="Ex: Churrascaria Gaúcha"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                            Descrição
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500 transition-colors duration-200"
                                  placeholder="Descreva seu estabelecimento...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                            Categoria *
                        </label>
                        <select id="category" 
                                name="category" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500 transition-colors duration-200"
                                required>
                            <option value="">Selecione uma categoria</option>
                            <option value="churrascaria" {{ old('category') == 'churrascaria' ? 'selected' : '' }}>Churrascaria</option>
                            <option value="açougue" {{ old('category') == 'açougue' ? 'selected' : '' }}>Açougue</option>
                            <option value="supermercado" {{ old('category') == 'supermercado' ? 'selected' : '' }}>Supermercado</option>
                            <option value="restaurante" {{ old('category') == 'restaurante' ? 'selected' : '' }}>Restaurante</option>
                            <option value="bar" {{ old('category') == 'bar' ? 'selected' : '' }}>Bar</option>
                            <option value="lanchonete" {{ old('category') == 'lanchonete' ? 'selected' : '' }}>Lanchonete</option>
                            <option value="delivery" {{ old('category') == 'delivery' ? 'selected' : '' }}>Delivery</option>
                            <option value="outros" {{ old('category') == 'outros' ? 'selected' : '' }}>Outros</option>
                        </select>
                        @error('category')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            Telefone
                        </label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500 transition-colors duration-200"
                               placeholder="(51) 99999-9999">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Endereço</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                            Endereço Completo *
                        </label>
                        <input type="text" 
                               id="address" 
                               name="address" 
                               value="{{ old('address') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500 transition-colors duration-200"
                               placeholder="Rua, número, bairro"
                               required>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">
                            Cidade *
                        </label>
                        <input type="text" 
                               id="city" 
                               name="city" 
                               value="{{ old('city', 'Porto Alegre') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500 transition-colors duration-200"
                               placeholder="Porto Alegre"
                               required>
                        @error('city')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-semibold text-gray-700 mb-2">
                            Estado *
                        </label>
                        <input type="text" 
                               id="state" 
                               name="state" 
                               value="{{ old('state', 'RS') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500 transition-colors duration-200"
                               placeholder="RS"
                               maxlength="2"
                               required>
                        @error('state')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="zip_code" class="block text-sm font-semibold text-gray-700 mb-2">
                            CEP *
                        </label>
                        <input type="text" 
                               id="zip_code" 
                               name="zip_code" 
                               value="{{ old('zip_code') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500 transition-colors duration-200"
                               placeholder="90000-000"
                               required>
                        @error('zip_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500 transition-colors duration-200"
                               placeholder="contato@estabelecimento.com">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-semibold text-gray-700 mb-2">
                            Website
                        </label>
                        <input type="url" 
                               id="website" 
                               name="website" 
                               value="{{ old('website') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500 transition-colors duration-200"
                               placeholder="https://www.estabelecimento.com">
                        @error('website')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Media -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Imagens</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="logo" class="block text-sm font-semibold text-gray-700 mb-2">
                            Logo
                        </label>
                        <input type="file" 
                               id="logo" 
                               name="logo" 
                               accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500 transition-colors duration-200">
                        <p class="text-sm text-gray-500 mt-1">PNG, JPG até 2MB</p>
                        @error('logo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cover_image" class="block text-sm font-semibold text-gray-700 mb-2">
                            Imagem de Capa
                        </label>
                        <input type="file" 
                               id="cover_image" 
                               name="cover_image" 
                               accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500 transition-colors duration-200">
                        <p class="text-sm text-gray-500 mt-1">PNG, JPG até 2MB</p>
                        @error('cover_image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="images" class="block text-sm font-semibold text-gray-700 mb-2">
                            Galeria de Imagens
                        </label>
                        <input type="file" 
                               id="images" 
                               name="images[]" 
                               accept="image/*"
                               multiple
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500 transition-colors duration-200">
                        <p class="text-sm text-gray-500 mt-1">Selecione múltiplas imagens (PNG, JPG até 2MB cada)</p>
                        @error('images')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('establishments.index') }}" 
                   class="px-8 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors duration-200 font-semibold">
                    Cancelar
                </a>
                <button type="submit" 
                        id="submit-btn"
                        class="px-8 py-3 bg-gradient-to-r from-churrasco-500 to-churrasco-600 text-white rounded-xl hover:from-churrasco-600 hover:to-churrasco-700 transition-all duration-300 transform hover:scale-105 font-semibold shadow-lg">
                    <span id="submit-text">Cadastrar Estabelecimento</span>
                </button>
            </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('establishment_search');
    const resultsDiv = document.getElementById('establishment_search_results');
    const hiddenInput = document.getElementById('existing_establishment_id');
    const clearBtn = document.getElementById('clear_search');
    const selectedInfo = document.getElementById('selected_establishment_info');
    const selectedName = document.getElementById('selected_name');
    const selectedAddress = document.getElementById('selected_address');
    const form = document.getElementById('establishment-form');
    const newEstablishmentForm = document.getElementById('new-establishment-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    let searchTimeout = null;
    let selectedEstablishment = null;

    // Handle search input
    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.trim();
        
        clearTimeout(searchTimeout);
        
        // If user is typing and there's a selection, clear it
        if (selectedEstablishment && query !== selectedEstablishment.display) {
            clearSelection();
        }
        
        if (query.length < 2) {
            resultsDiv.classList.add('hidden');
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`{{ route('api.establishments.search') }}?q=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.establishments.length > 0) {
                    displayResults(data.establishments);
                } else {
                    resultsDiv.innerHTML = '<div class="p-4 text-sm text-gray-500">Nenhum estabelecimento encontrado</div>';
                    resultsDiv.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error searching establishments:', error);
                resultsDiv.classList.add('hidden');
            });
        }, 300);
    });

    // Display search results
    function displayResults(establishments) {
        resultsDiv.innerHTML = '';
        
        establishments.forEach(establishment => {
            const item = document.createElement('div');
            item.className = 'p-4 hover:bg-churrasco-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors';
            item.innerHTML = `
                <div class="font-semibold text-gray-900">${establishment.name}</div>
                <div class="text-sm text-gray-600 mt-1">${establishment.address || establishment.city || ''}</div>
            `;
            
            item.addEventListener('click', function() {
                selectEstablishment(establishment);
            });
            
            resultsDiv.appendChild(item);
        });
        
        resultsDiv.classList.remove('hidden');
    }

    // Select an establishment
    function selectEstablishment(establishment) {
        selectedEstablishment = establishment;
        searchInput.value = establishment.display;
        hiddenInput.value = establishment.id;
        resultsDiv.classList.add('hidden');
        
        // Show selected info
        selectedName.textContent = establishment.name;
        selectedAddress.textContent = establishment.address || establishment.city || '';
        selectedInfo.classList.remove('hidden');
        clearBtn.classList.remove('hidden');
        
        updateFormState();
    }

    // Clear selection
    function clearSelection() {
        selectedEstablishment = null;
        searchInput.value = '';
        hiddenInput.value = '';
        selectedInfo.classList.add('hidden');
        clearBtn.classList.add('hidden');
        updateFormState();
    }

    // Clear button handler
    clearBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        clearSelection();
        searchInput.focus();
    });

    // Update form state based on selection
    function updateFormState() {
        if (selectedEstablishment) {
            // Hide new establishment form
            newEstablishmentForm.style.display = 'none';
            submitText.textContent = 'Vincular-se ao Estabelecimento';
            
            // Make all fields in new form not required
            const requiredFields = newEstablishmentForm.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                field.removeAttribute('required');
            });
        } else {
            // Show new establishment form
            newEstablishmentForm.style.display = 'block';
            submitText.textContent = 'Cadastrar Estabelecimento';
            
            // Make all fields required again
            const requiredFields = newEstablishmentForm.querySelectorAll('input[required], select[required], textarea[required]');
            requiredFields.forEach(field => {
                field.setAttribute('required', 'required');
            });
        }
    }

    // Close results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target) && !clearBtn.contains(e.target)) {
            resultsDiv.classList.add('hidden');
        }
    });

    // Handle keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            resultsDiv.classList.add('hidden');
            if (selectedEstablishment) {
                searchInput.blur();
            }
        }
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        if (selectedEstablishment) {
            // If linking to existing, we don't need to validate new form fields
            return true;
        }
        // Otherwise, normal validation will occur
    });
});
</script>
@endpush
@endsection


