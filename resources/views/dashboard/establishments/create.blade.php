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
        <form action="{{ route('establishments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
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
                        class="px-8 py-3 bg-gradient-to-r from-churrasco-500 to-churrasco-600 text-white rounded-xl hover:from-churrasco-600 hover:to-churrasco-700 transition-all duration-300 transform hover:scale-105 font-semibold shadow-lg">
                    Cadastrar Estabelecimento
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


