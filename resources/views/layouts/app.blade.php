<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#dc2626">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Churrasco POA">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="msapplication-TileColor" content="#dc2626">
    <meta name="msapplication-config" content="/browserconfig.xml">

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('images/icons/icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="96x96" href="{{ asset('images/icons/icon-96x96.png') }}">
    <link rel="apple-touch-icon" sizes="128x128" href="{{ asset('images/icons/icon-128x128.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('images/icons/icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/icons/icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="384x384" href="{{ asset('images/icons/icon-384x384.png') }}">
    <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('images/icons/icon-512x512.png') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('images/icons/icon-512x512.png') }}">

    <title>{{ config('app.name', 'Porto Alegre Capital Mundial do Churrasco') }} - @yield('title')</title>
    
    <!-- Default meta tags -->
    <meta name="description" content="@yield('meta_description', 'Descubra as melhores experiências de churrasco em Porto Alegre. Explore estabelecimentos verificados, produtos especiais, promoções exclusivas e serviços artesanais.')">
    <meta name="keywords" content="@yield('meta_keywords', 'churrasco, porto alegre, churrascarias, açougues, produtos, serviços, capital mundial do churrasco')">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ config('app.name', 'Porto Alegre Capital Mundial do Churrasco') }} - @yield('title')">
    <meta property="og:description" content="@yield('meta_description', 'Descubra as melhores experiências de churrasco em Porto Alegre.')">
    <meta property="og:image" content="@yield('og_image', asset('images/hero-porto-alegre.jpg'))">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ config('app.name', 'Porto Alegre Capital Mundial do Churrasco') }} - @yield('title')">
    <meta name="twitter:description" content="@yield('meta_description', 'Descubra as melhores experiências de churrasco em Porto Alegre.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/hero-porto-alegre.jpg'))">
    
    @stack('meta')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:wght@300;400;500;600;700;800;900" rel="stylesheet" />

    <!-- CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Force hide auth buttons on mobile immediately */
        @media (max-width: 1023px) {
            .auth-buttons-desktop {
                display: none !important;
                visibility: hidden !important;
                width: 0 !important;
                height: 0 !important;
                overflow: hidden !important;
                opacity: 0 !important;
                pointer-events: none !important;
                position: absolute !important;
                left: -9999px !important;
            }
            
            .mobile-menu-btn-wrapper,
            #mobile-menu-button {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            #mobile-menu-button {
                display: inline-flex !important;
            }
        }
    </style>
    <script>
        // Hide auth buttons immediately on mobile (runs before DOM is ready)
        (function() {
            if (window.innerWidth < 1024) {
                var style = document.createElement('style');
                style.textContent = '.auth-buttons-desktop { display: none !important; visibility: hidden !important; width: 0 !important; height: 0 !important; overflow: hidden !important; opacity: 0 !important; pointer-events: none !important; position: absolute !important; left: -9999px !important; }';
                document.head.appendChild(style);
            }
        })();
    </script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white/80 backdrop-blur-lg shadow-lg fixed top-0 left-0 right-0 z-50 border-b border-gray-200/50">
            <div class="max-w-7xl mx-auto px-2 sm:px-3 md:px-4 lg:px-8 w-full">
                <div class="flex justify-between items-center h-16 sm:h-20 w-full">
                    <div class="flex items-center flex-1 min-w-0 pr-2">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center">
                                <div>
                                    <div class="text-[8px] xs:text-[9px] sm:text-xs md:text-sm lg:text-base xl:text-lg font-black text-gray-900 leading-tight max-w-[90px] xs:max-w-[110px] sm:max-w-[130px] md:max-w-[160px] lg:max-w-none truncate">Porto Alegre Capital Mundial do Churrasco</div>
                                </div>
                            </a>
                        </div>

                        <!-- Navigation Links - Desktop -->
                        <div class="hidden lg:ml-10 lg:flex lg:space-x-8">
                            <a href="{{ route('recipes.guides') }}" class="text-gray-700 hover:text-churrasco-600 px-3 py-2 rounded-md text-sm font-semibold transition-colors duration-200 inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Guias
                            </a>
                            <a href="{{ route('mapa') }}" class="text-gray-700 hover:text-churrasco-600 px-3 py-2 rounded-md text-sm font-semibold transition-colors duration-200 inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Mapa
                            </a>
                            <a href="{{ route('products') }}" class="text-gray-700 hover:text-churrasco-600 px-3 py-2 rounded-md text-sm font-semibold transition-colors duration-200 inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                Marketplace
                            </a>
                        </div>
                    </div>

                    <!-- Auth Links - Desktop Only (hidden on mobile) -->
                    <div class="auth-buttons-desktop items-center space-x-2 lg:space-x-3" style="display: none;">
                        @auth
                            <!-- User Avatar and Name -->
                            <div class="hidden lg:flex items-center space-x-3">
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

                            <a href="{{ route('dashboard') }}" class="bg-churrasco-500 hover:bg-churrasco-600 text-white px-4 lg:px-6 py-2 rounded-lg text-xs sm:text-sm font-semibold transition-colors duration-200">
                                Painel
                            </a>
                            <a href="{{ route('favorites.index') }}" class="hidden lg:inline-flex text-gray-700 hover:text-churrasco-600 px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                </svg>
                                Favoritos
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="hidden lg:inline">
                                @csrf
                                <button type="submit" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 lg:px-6 py-2 rounded-lg text-xs sm:text-sm font-semibold transition-colors duration-200">
                                    Sair
                                </button>
                            </form>
                        @else
                            <a href="{{ route('register') }}" class="bg-churrasco-500 hover:bg-churrasco-600 text-white px-3 sm:px-4 lg:px-6 py-2 rounded-lg text-xs sm:text-sm font-semibold transition-colors duration-200 whitespace-nowrap">
                                <span class="hidden sm:inline">Cadastrar estabelecimento</span>
                                <span class="sm:hidden">Cadastrar</span>
                            </a>
                            <a href="{{ route('login') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-3 sm:px-4 lg:px-6 py-2 rounded-lg text-xs sm:text-sm font-semibold transition-colors duration-200">
                                Entrar
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile menu button - Always visible on mobile/tablet -->
                    <div class="mobile-menu-btn-wrapper flex items-center lg:hidden flex-shrink-0">
                        <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-2.5 rounded-md text-gray-700 hover:text-churrasco-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-churrasco-500 transition-colors" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Abrir menu principal</span>
                            <svg class="block h-6 w-6 text-gray-700" id="menu-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg class="hidden h-6 w-6 text-gray-700" id="close-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="hidden lg:hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200 shadow-lg">
                    <a href="{{ route('recipes.guides') }}" class="text-gray-700 hover:text-churrasco-600 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Guias
                        </div>
                    </a>
                    <a href="{{ route('mapa') }}" class="text-gray-700 hover:text-churrasco-600 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Mapa
                        </div>
                    </a>
                    <a href="{{ route('products') }}" class="text-gray-700 hover:text-churrasco-600 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Marketplace
                        </div>
                    </a>
                    @auth
                        <div class="border-t border-gray-200 pt-4 pb-3">
                            <div class="flex items-center px-3 mb-3">
                                @if(auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar }}"
                                         alt="{{ auth()->user()->name }}"
                                         class="w-10 h-10 rounded-full"
                                         crossorigin="anonymous"
                                         referrerpolicy="no-referrer"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-10 h-10 bg-gradient-to-br from-churrasco-500 to-churrasco-600 rounded-full flex items-center justify-center" style="display: none;">
                                        <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                @else
                                    <div class="w-10 h-10 bg-gradient-to-br from-churrasco-500 to-churrasco-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="ml-3">
                                    <div class="text-base font-medium text-gray-800">{{ auth()->user()->name }}</div>
                                </div>
                            </div>
                            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-churrasco-600 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">
                                Painel
                            </a>
                            <a href="{{ route('favorites.index') }}" class="text-gray-700 hover:text-churrasco-600 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">
                                Favoritos
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left text-gray-700 hover:text-churrasco-600 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium">
                                    Sair
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="border-t border-gray-200 pt-4 pb-3 space-y-2">
                            <a href="{{ route('register') }}" class="block w-full text-center bg-churrasco-500 hover:bg-churrasco-600 text-white px-4 py-2 rounded-lg text-base font-semibold transition-colors duration-200">
                                Cadastrar estabelecimento
                            </a>
                            <a href="{{ route('login') }}" class="block w-full text-center bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-base font-semibold transition-colors duration-200">
                                Entrar
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-grow pt-16 sm:pt-20 pb-20 lg:pb-0">
            @yield('content')
        </main>

        <!-- Mobile Bottom Navigation -->
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-50">
            <div class="flex justify-around items-center h-16">
                <a href="{{ route('home') }}" class="flex flex-col items-center justify-center flex-1 px-2 py-1 text-gray-600 hover:text-churrasco-600 transition-colors {{ request()->routeIs('home') ? 'text-churrasco-600' : '' }}">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-xs font-medium">Início</span>
                </a>
                <a href="{{ route('recipes.guides') }}" class="flex flex-col items-center justify-center flex-1 px-2 py-1 text-gray-600 hover:text-churrasco-600 transition-colors {{ request()->routeIs('recipes.guides') ? 'text-churrasco-600' : '' }}">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="text-xs font-medium">Guias</span>
                </a>
                <a href="{{ route('mapa') }}" class="flex flex-col items-center justify-center flex-1 px-2 py-1 text-gray-600 hover:text-churrasco-600 transition-colors {{ request()->routeIs('mapa') ? 'text-churrasco-600' : '' }}">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-xs font-medium">Mapa</span>
                </a>
                <a href="{{ route('products') }}" class="flex flex-col items-center justify-center flex-1 px-2 py-1 text-gray-600 hover:text-churrasco-600 transition-colors {{ request()->routeIs('products') ? 'text-churrasco-600' : '' }}">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="text-xs font-medium">Marketplace</span>
                </a>
                <button type="button" id="mobile-menu-button-bottom" class="flex flex-col items-center justify-center flex-1 px-2 py-1 text-gray-600 hover:text-churrasco-600 transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <span class="text-xs font-medium">Menu</span>
                </button>
            </div>
        </nav>

        <!-- Footer -->
        <footer class="bg-blue-900 text-white hidden lg:block">
            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Brand -->
                    <div class="md:col-span-1">
                        <div class="mb-6">
                            <div>
                                <div class="text-base sm:text-lg md:text-xl font-black text-white leading-tight">Porto Alegre Capital Mundial do Churrasco</div>
                            </div>
                        </div>
                        <p class="text-gray-400 text-sm leading-relaxed mb-6">
                            Conectando tradição, qualidade e inovação na capital brasileira do churrasco.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-churrasco-600 transition-colors duration-200" aria-label="Follow us on Twitter">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-churrasco-600 transition-colors duration-200" aria-label="Follow us on LinkedIn">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z" />
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-churrasco-600 transition-colors duration-200" aria-label="Follow us on GitHub">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Links -->
                    <div>
                        <h3 class="text-lg font-bold mb-6">Plataforma</h3>
                        <ul class="space-y-3">
                            <li><a href="{{ route('mapa') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Mapa interativo</a></li>
                            <li><a href="{{ route('products') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Marketplace</a></li>
                            <li><a href="{{ route('recipes.guides') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Receitas e vídeos</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Parceiros</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold mb-6">Suporte</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Central de ajuda</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Contato</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Perguntas frequentes</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Termos de uso</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold mb-6">Newsletter</h3>
                        <p class="text-gray-400 text-sm mb-4">
                            Inscreva-se para receber lançamentos de produtos, ofertas curadas e atualizações da plataforma.
                        </p>
                        <div class="flex">
                            <label for="newsletter-email" class="sr-only">Endereço de e-mail</label>
                            <input id="newsletter-email" type="email" placeholder="Seu e-mail" class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-l-lg text-white placeholder-gray-400 focus:outline-none focus:border-churrasco-500">
                            <button class="bg-churrasco-500 hover:bg-churrasco-600 text-white px-4 py-2 rounded-r-lg transition-colors duration-200" aria-label="Inscrever-se na newsletter">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-800 my-8">

                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Porto Alegre Capital Mundial do Churrasco') }}. All rights reserved.
                    </p>
                    <p class="text-gray-500 text-sm mt-2 md:mt-0">
                        Crafted with ?? by the <span class="text-churrasco-400 font-semibold">Conext</span> team.
                    </p>
                </div>
            </div>
        </footer>
    </div>
    
    @stack('scripts')
    
    <script>
        // Hide auth buttons and show mobile menu button on mobile
        (function() {
            'use strict';
            
            function hideAuthButtonsOnMobile() {
                if (window.innerWidth < 1024) {
                    const authButtons = document.querySelectorAll('.auth-buttons-desktop');
                    authButtons.forEach(function(btn) {
                        if (btn) {
                            btn.style.setProperty('display', 'none', 'important');
                            btn.style.setProperty('visibility', 'hidden', 'important');
                            btn.style.setProperty('width', '0', 'important');
                            btn.style.setProperty('height', '0', 'important');
                            btn.style.setProperty('overflow', 'hidden', 'important');
                            btn.style.setProperty('opacity', '0', 'important');
                            btn.style.setProperty('pointer-events', 'none', 'important');
                            btn.style.setProperty('position', 'absolute', 'important');
                            btn.style.setProperty('left', '-9999px', 'important');
                        }
                    });
                    
                    // Show mobile menu button
                    const menuBtnWrapper = document.querySelector('.mobile-menu-btn-wrapper');
                    const menuBtn = document.getElementById('mobile-menu-button');
                    if (menuBtnWrapper) {
                        menuBtnWrapper.style.setProperty('display', 'flex', 'important');
                        menuBtnWrapper.style.setProperty('visibility', 'visible', 'important');
                    }
                    if (menuBtn) {
                        menuBtn.style.setProperty('display', 'inline-flex', 'important');
                        menuBtn.style.setProperty('visibility', 'visible', 'important');
                        menuBtn.style.setProperty('opacity', '1', 'important');
                    }
                } else {
                    // Show auth buttons on desktop
                    const authButtons = document.querySelectorAll('.auth-buttons-desktop');
                    authButtons.forEach(function(btn) {
                        if (btn) {
                            btn.style.removeProperty('display');
                            btn.style.removeProperty('visibility');
                            btn.style.removeProperty('width');
                            btn.style.removeProperty('height');
                            btn.style.removeProperty('overflow');
                            btn.style.removeProperty('opacity');
                            btn.style.removeProperty('pointer-events');
                            btn.style.removeProperty('position');
                            btn.style.removeProperty('left');
                        }
                    });
                }
            }
            
            // Run immediately
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(hideAuthButtonsOnMobile, 0);
                });
            } else {
                setTimeout(hideAuthButtonsOnMobile, 0);
            }
            
            // Run on resize
            window.addEventListener('resize', hideAuthButtonsOnMobile);
            
            // Run after a short delay to ensure DOM is ready
            setTimeout(hideAuthButtonsOnMobile, 100);
        })();
        
        // Mobile menu toggle
        (function() {
            'use strict';
            
            function initMobileMenu() {
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const mobileMenuButtonBottom = document.getElementById('mobile-menu-button-bottom');
                const mobileMenu = document.getElementById('mobile-menu');
                const menuIcon = document.getElementById('menu-icon');
                const closeIcon = document.getElementById('close-icon');
                
                if (!mobileMenu || !menuIcon || !closeIcon) {
                    console.warn('Mobile menu elements not found');
                    return;
                }
                
                function toggleMenu() {
                    const isHidden = mobileMenu.classList.contains('hidden');
                    
                    if (isHidden) {
                        mobileMenu.classList.remove('hidden');
                        if (menuIcon) menuIcon.classList.add('hidden');
                        if (closeIcon) closeIcon.classList.remove('hidden');
                        if (mobileMenuButton) mobileMenuButton.setAttribute('aria-expanded', 'true');
                        document.body.style.overflow = 'hidden';
                    } else {
                        mobileMenu.classList.add('hidden');
                        if (menuIcon) menuIcon.classList.remove('hidden');
                        if (closeIcon) closeIcon.classList.add('hidden');
                        if (mobileMenuButton) mobileMenuButton.setAttribute('aria-expanded', 'false');
                        document.body.style.overflow = '';
                    }
                }
                
                if (mobileMenuButton) {
                    mobileMenuButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        toggleMenu();
                    });
                }
                
                if (mobileMenuButtonBottom) {
                    mobileMenuButtonBottom.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        toggleMenu();
                    });
                }
                
                // Close menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenu.classList.contains('hidden')) {
                        const isClickInside = (mobileMenuButton && mobileMenuButton.contains(event.target)) ||
                                            (mobileMenuButtonBottom && mobileMenuButtonBottom.contains(event.target)) ||
                                            mobileMenu.contains(event.target);
                        if (!isClickInside) {
                            toggleMenu();
                        }
                    }
                });
                
                // Close menu on escape key
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
                        toggleMenu();
                    }
                });
                
                // Close menu when clicking on a link inside
                const mobileMenuLinks = mobileMenu.querySelectorAll('a, button[type="submit"]');
                mobileMenuLinks.forEach(function(link) {
                    link.addEventListener('click', function() {
                        toggleMenu();
                    });
                });
            }
            
            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initMobileMenu);
            } else {
                initMobileMenu();
            }
        })();
    </script>
</body>
</html>
