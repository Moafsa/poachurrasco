<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'POA Capital do Churrasco') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:wght@300;400;500;600;700;800;900" rel="stylesheet" />

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('app.css') }}">
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white/80 backdrop-blur-lg shadow-lg fixed top-0 left-0 right-0 z-50 border-b border-gray-200/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center">
                                <div>
                                    <div class="text-sm font-bold text-orange-500">POA</div>
                                    <div class="text-xl font-black text-gray-900">Capital do Churrasco</div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Navigation Links -->
                        <div class="hidden lg:ml-10 lg:flex lg:space-x-8">
                            <a href="{{ route('recipes') }}" class="text-gray-700 hover:text-churrasco-600 px-3 py-2 rounded-md text-sm font-semibold transition-colors duration-200 inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Guia
                            </a>
                            <a href="{{ route('mapa') }}" class="text-gray-700 hover:text-churrasco-600 px-3 py-2 rounded-md text-sm font-semibold transition-colors duration-200 inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Mapa
                            </a>
                            <a href="{{ route('products') }}" class="text-gray-700 hover:text-churrasco-600 px-3 py-2 rounded-md text-sm font-semibold transition-colors duration-200 inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                Marketplace
                            </a>
                        </div>
                    </div>
                    
                    <!-- Auth Links -->
                    <div class="flex items-center space-x-3">
                        @auth
                            <!-- User Avatar and Name -->
                            <div class="flex items-center space-x-3">
                                @if(auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar }}" 
                                         alt="{{ auth()->user()->name }}" 
                                         class="w-8 h-8 rounded-full"
                                         crossorigin="anonymous"
                                         referrerpolicy="no-referrer"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-8 h-8 bg-gradient-to-br from-churrasco-500 to-churrasco-600 rounded-full flex items-center justify-center" style="display: none;">
                                        <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                @else
                                    <div class="w-8 h-8 bg-gradient-to-br from-churrasco-500 to-churrasco-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <span class="text-gray-700 text-sm font-medium">{{ auth()->user()->name }}</span>
                            </div>
                            
                            <a href="{{ route('dashboard') }}" class="bg-churrasco-500 hover:bg-churrasco-600 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">
                                Dashboard
                            </a>
                            <a href="{{ route('favorites.index') }}" class="text-gray-700 hover:text-churrasco-600 px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                                Favoritos
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">
                                    Sair
                                </button>
                            </form>
                        @else
                            <a href="{{ route('register') }}" class="bg-churrasco-500 hover:bg-churrasco-600 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">
                                Registrar Estabelecimento
                            </a>
                            <a href="{{ route('login') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">
                                Entrar
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-grow pt-20">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-blue-900 text-white">
            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Brand -->
                    <div class="md:col-span-1">
                        <div class="mb-6">
                            <div>
                                <div class="text-sm font-bold text-orange-400">POA</div>
                                <div class="text-xl font-black">Capital do Churrasco</div>
                            </div>
                        </div>
                        <p class="text-gray-400 text-sm leading-relaxed mb-6">
                            Conectando tradição, qualidade e inovação na capital gaúcha do churrasco.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-churrasco-600 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-churrasco-600 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-churrasco-600 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Links -->
                    <div>
                        <h3 class="text-lg font-bold mb-6">Plataforma</h3>
                        <ul class="space-y-3">
                            <li><a href="#mapa" class="text-gray-400 hover:text-white transition-colors duration-200">Mapa Interativo</a></li>
                            <li><a href="#marketplace" class="text-gray-400 hover:text-white transition-colors duration-200">Marketplace</a></li>
                            <li><a href="#receitas" class="text-gray-400 hover:text-white transition-colors duration-200">Receitas & Vídeos</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Parceiros</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-bold mb-6">Suporte</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Central de Ajuda</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Contato</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">FAQ</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Termos de Uso</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-bold mb-6">Newsletter</h3>
                        <p class="text-gray-400 text-sm mb-4">
                            Receba as melhores ofertas e novidades da plataforma.
                        </p>
                        <div class="flex">
                            <input type="email" placeholder="Seu email" class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-l-lg text-white placeholder-gray-400 focus:outline-none focus:border-churrasco-500">
                            <button class="bg-churrasco-500 hover:bg-churrasco-600 text-white px-4 py-2 rounded-r-lg transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <hr class="border-gray-800 my-8">
                
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
                    </p>
                    <p class="text-gray-500 text-sm mt-2 md:mt-0">
                        Desenvolvido com ❤️ pela equipe <span class="text-churrasco-400 font-semibold">Conext</span>
                    </p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>