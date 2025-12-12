@extends('layouts.app')

@section('title', 'Branding Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900">Gerenciamento de Branding</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Gerencie a logo do site e o selo de qualidade</p>
            </div>
            <a href="{{ route('super-admin.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Logo Section -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Logo do Site</h2>
                <p class="text-sm text-gray-600 mb-6">A logo aparece no cabeçalho do site em todas as páginas</p>
                
                @if($logoContent && $logoContent->content)
                    <div class="mb-6">
                        <div class="bg-gray-50 rounded-lg p-4 border-2 border-dashed border-gray-200 flex items-center justify-center">
                            <img 
                                src="{{ $logoContent->content }}" 
                                alt="Site Logo" 
                                class="max-w-full max-h-32 object-contain"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                            >
                            <div style="display: none;" class="text-gray-400 text-sm">Imagem não encontrada</div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 text-center">Logo atual</p>
                    </div>
                    
                    <form method="POST" action="{{ route('super-admin.branding.logo.delete') }}" class="mb-4" onsubmit="return confirm('Tem certeza que deseja deletar a logo?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                            Deletar Logo
                        </button>
                    </form>
                @endif

                <form method="POST" action="{{ route('super-admin.branding.logo.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $logoContent && $logoContent->content ? 'Substituir Logo' : 'Enviar Logo' }}
                        </label>
                        <input 
                            type="file" 
                            name="logo" 
                            accept="image/jpeg,image/png,image/webp,image/svg+xml"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        <p class="text-xs text-gray-500 mt-1">Formatos aceitos: JPG, PNG, WEBP, SVG (máx. 5MB)</p>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        {{ $logoContent && $logoContent->content ? 'Atualizar Logo' : 'Enviar Logo' }}
                    </button>
                </form>
            </div>

            <!-- Seal Section -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Selo de Qualidade</h2>
                <p class="text-sm text-gray-600 mb-6">O selo aparece na seção de qualidade na página inicial</p>
                
                @if($sealContent && $sealContent->content)
                    <div class="mb-6">
                        <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-lg p-4 border-2 border-dashed border-amber-200 flex items-center justify-center">
                            <img 
                                src="{{ $sealContent->content }}" 
                                alt="Quality Seal" 
                                class="max-w-full max-h-32 object-contain"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                            >
                            <div style="display: none;" class="text-gray-400 text-sm">Imagem não encontrada</div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 text-center">Selo atual</p>
                    </div>
                    
                    <form method="POST" action="{{ route('super-admin.branding.seal.delete') }}" class="mb-4" onsubmit="return confirm('Tem certeza que deseja deletar o selo?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                            Deletar Selo
                        </button>
                    </form>
                @endif

                <form method="POST" action="{{ route('super-admin.branding.seal.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $sealContent && $sealContent->content ? 'Substituir Selo' : 'Enviar Selo' }}
                        </label>
                        <input 
                            type="file" 
                            name="seal" 
                            accept="image/jpeg,image/png,image/webp,image/svg+xml"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        <p class="text-xs text-gray-500 mt-1">Formatos aceitos: JPG, PNG, WEBP, SVG (máx. 5MB)</p>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                        {{ $sealContent && $sealContent->content ? 'Atualizar Selo' : 'Enviar Selo' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Instructions -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">📋 Instruções</h3>
            <ul class="space-y-2 text-sm text-blue-800">
                <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span><strong>Logo:</strong> A logo será exibida no cabeçalho do site. Recomendamos usar uma imagem com fundo transparente (PNG) e proporção horizontal.</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span><strong>Selo:</strong> O selo de qualidade aparece na seção especial da página inicial. Use uma imagem quadrada ou circular para melhor visualização.</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span>Após o upload, as imagens serão salvas automaticamente e aparecerão no site imediatamente.</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection






