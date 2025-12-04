@extends('layouts.app')

@section('title', 'Porto Alegre Capital Mundial do Churrasco')

@push('meta')
    @php
        $hasLocalJpeg = file_exists(public_path('images/hero-porto-alegre.jpg'));
        $hasLocalWebP = file_exists(public_path('images/hero-porto-alegre.webp'));
        $heroImage = $hasLocalJpeg ? asset('images/hero-porto-alegre.jpg') : ($hasLocalWebP ? asset('images/hero-porto-alegre.webp') : 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
        // Only preload if it's a local image (not external URL)
        $shouldPreload = $hasLocalJpeg || $hasLocalWebP;
    @endphp
    <meta name="description" content="Descubra as melhores experiências de churrasco na capital mundial do churrasco. Explore estabelecimentos verificados, produtos especiais, promoções exclusivas e serviços artesanais da plataforma Porto Alegre Capital Mundial do Churrasco.">
    <meta property="og:image" content="{{ $heroImage }}">
    <meta name="twitter:image" content="{{ $heroImage }}">
    @if($shouldPreload)
        <link rel="preload" as="image" href="{{ $heroImage }}">
    @endif
@endpush

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp
<div class="min-h-screen w-full overflow-x-hidden">
    <section id="hero-section-main" class="relative min-h-[70vh] sm:min-h-[85vh] md:min-h-[90vh] overflow-hidden w-full" role="banner" aria-label="Hero section">
        <!-- Background Image/Video with Parallax Effect -->
        <div class="absolute inset-0 hero-background" style="z-index: 0;">
            @php
                // Initialize variables
                $useDatabaseHero = false;
                $primaryImage = null;
                $primaryVideo = null;
                
                // Check for hero section from database first (with safety checks)
                if (isset($heroSection) && $heroSection !== null && is_object($heroSection) && isset($heroSection->is_active) && $heroSection->is_active) {
                    $useDatabaseHero = true;
                    
                    // Get primary image and video from loaded media if available
                    try {
                        if (isset($heroSection->media) && $heroSection->media !== null && is_countable($heroSection->media) && count($heroSection->media) > 0) {
                            $imageMedia = $heroSection->media->where('type', 'image')->sortBy('display_order')->first();
                            $videoMedia = $heroSection->media->where('type', 'video')->sortBy('display_order')->first();
                            $primaryImage = ($imageMedia && isset($imageMedia->media_path)) ? $imageMedia->media_path : null;
                            $primaryVideo = ($videoMedia && isset($videoMedia->media_path)) ? $videoMedia->media_path : null;
                        }
                    } catch (\Exception $e) {
                        // If there's an error accessing media, fall back to local files
                        $useDatabaseHero = false;
                    } catch (\Error $e) {
                        // Catch fatal errors as well
                        $useDatabaseHero = false;
                    }
                }
                
                // Fallback: Check for local images (WebP and JPEG) and video
                $hasLocalWebP = file_exists(public_path('images/hero-porto-alegre.webp'));
                $hasLocalJpeg = file_exists(public_path('images/hero-porto-alegre.jpg'));
                $hasLocalVideo = file_exists(public_path('images/hero-porto-alegre.mp4'));
                
                // Fallback to Unsplash - Porto Alegre image (direct URL)
                $unsplashImage = 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=90';
            @endphp
            
            @if($useDatabaseHero && $heroSection && isset($heroSection->type) && $heroSection->type === 'video' && $primaryVideo)
                <!-- Video from database -->
                <video 
                    autoplay 
                    muted 
                    loop 
                    playsinline
                    class="h-full w-full object-cover hero-video"
                    poster="{{ $primaryImage ? Storage::disk('public')->url($primaryImage) : $unsplashImage }}"
                    aria-label="{{ $heroSection->title ?? 'Hero video background' }}"
                >
                    <source src="{{ Storage::disk('public')->url($primaryVideo) }}" type="video/mp4">
                    @if($primaryImage)
                        <img 
                            src="{{ Storage::disk('public')->url($primaryImage) }}" 
                            alt="{{ $heroSection->title ?? 'Hero image' }}"
                            class="h-full w-full object-cover hero-image"
                            loading="eager"
                            decoding="async"
                        >
                    @endif
                </video>
            @elseif($useDatabaseHero && $heroSection && isset($heroSection->type) && $heroSection->type === 'slideshow' && isset($heroSection->media) && $heroSection->media && method_exists($heroSection->media, 'count') && $heroSection->media->count() > 0)
                <!-- Slideshow from database -->
                <div class="hero-slideshow h-full w-full">
                    @foreach($heroSection->media->sortBy('display_order') as $index => $media)
                        <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" style="display: {{ $index === 0 ? 'block' : 'none' }};">
                            @if($media->type === 'image')
                                <img 
                                    src="{{ Storage::disk('public')->url($media->media_path) }}" 
                                    alt="{{ $media->alt_text ?? $heroSection->title }}"
                                    class="h-full w-full object-cover hero-image"
                                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                    decoding="async"
                                >
                            @elseif($media->type === 'video')
                                <video 
                                    autoplay 
                                    muted 
                                    loop 
                                    playsinline
                                    class="h-full w-full object-cover hero-video"
                                >
                                    <source src="{{ Storage::disk('public')->url($media->media_path) }}" type="video/mp4">
                                </video>
                            @endif
                        </div>
                    @endforeach
                </div>
            @elseif($useDatabaseHero && $heroSection && isset($heroSection->type) && $primaryImage)
                <!-- Single image from database -->
                <img 
                    src="{{ Storage::disk('public')->url($primaryImage) }}" 
                    alt="{{ $heroSection->title ?? 'Hero image' }}"
                    class="h-full w-full object-cover hero-image"
                    loading="eager"
                    decoding="async"
                    fetchpriority="high"
                >
            @elseif($hasLocalVideo)
                <!-- Video Background (if available) -->
                <video 
                    autoplay 
                    muted 
                    loop 
                    playsinline
                    class="h-full w-full object-cover hero-video"
                    poster="{{ $hasLocalJpeg ? asset('images/hero-porto-alegre.jpg') : ($hasLocalWebP ? asset('images/hero-porto-alegre.webp') : $unsplashImage) }}"
                    aria-label="Porto Alegre cityscape video background"
                >
                    <source src="{{ asset('images/hero-porto-alegre.mp4') }}" type="video/mp4">
                    <!-- Fallback for browsers that don't support video -->
                    <img 
                        src="{{ $hasLocalJpeg ? asset('images/hero-porto-alegre.jpg') : ($hasLocalWebP ? asset('images/hero-porto-alegre.webp') : $unsplashImage) }}" 
                        alt="Porto Alegre waterfront showing historic industrial building with tall chimney, modern cityscape, and Guaíba River"
                        class="h-full w-full object-cover hero-image"
                        loading="eager"
                        decoding="async"
                    >
                </video>
            @else
                <!-- Picture element for optimized image loading with WebP and responsive srcset support -->
                <picture>
                    @if($hasLocalWebP)
                        <!-- WebP sources with responsive sizes -->
                        <source 
                            media="(min-width: 1920px)" 
                            srcset="{{ asset('images/hero-porto-alegre.webp') }}" 
                            type="image/webp"
                        >
                        <source 
                            media="(min-width: 1280px)" 
                            srcset="{{ asset('images/hero-porto-alegre.webp') }}" 
                            type="image/webp"
                        >
                        <source 
                            media="(min-width: 768px)" 
                            srcset="{{ asset('images/hero-porto-alegre.webp') }}" 
                            type="image/webp"
                        >
                        <source 
                            srcset="{{ asset('images/hero-porto-alegre.webp') }}" 
                            type="image/webp"
                        >
                    @endif
                    @if($hasLocalJpeg)
                        <!-- JPEG sources with responsive sizes -->
                        <source 
                            media="(min-width: 1920px)" 
                            srcset="{{ asset('images/hero-porto-alegre.jpg') }}" 
                            type="image/jpeg"
                        >
                        <source 
                            media="(min-width: 1280px)" 
                            srcset="{{ asset('images/hero-porto-alegre.jpg') }}" 
                            type="image/jpeg"
                        >
                        <source 
                            media="(min-width: 768px)" 
                            srcset="{{ asset('images/hero-porto-alegre.jpg') }}" 
                            type="image/jpeg"
                        >
                        <img 
                            src="{{ asset('images/hero-porto-alegre.jpg') }}" 
                            alt="Porto Alegre waterfront showing historic industrial building with tall chimney, modern cityscape, and Guaíba River"
                            class="h-full w-full object-cover hero-image"
                            loading="eager"
                            decoding="async"
                            fetchpriority="high"
                            width="1920"
                            height="1080"
                        >
                    @else
                        <!-- Unsplash fallback with responsive query parameters -->
                        <source 
                            media="(min-width: 1920px)" 
                            srcset="https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=2560&q=80" 
                            type="image/jpeg"
                        >
                        <source 
                            media="(min-width: 1280px)" 
                            srcset="https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                            type="image/jpeg"
                        >
                        <source 
                            media="(min-width: 768px)" 
                            srcset="https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=1280&q=80" 
                            type="image/jpeg"
                        >
                        <img 
                            src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=90" 
                            alt="Porto Alegre waterfront showing historic industrial building with tall chimney, modern cityscape, and Guaíba River"
                            class="h-full w-full object-cover hero-image"
                            loading="eager"
                            decoding="async"
                            fetchpriority="high"
                            width="1920"
                            height="1080"
                            style="display: block; opacity: 1;"
                            onerror="this.style.display='none'; console.error('Image failed to load');"
                        >
                    @endif
                </picture>
            @endif
            
            <!-- Loading skeleton overlay -->
            <div class="absolute inset-0 hero-skeleton bg-gradient-to-br from-gray-800 via-gray-700 to-gray-800 animate-pulse" aria-hidden="true"></div>
            
            <!-- Gradient Overlay - Very light to show image clearly -->
            <div class="absolute inset-0 bg-gradient-to-br from-gray-900/25 via-orange-900/20 to-gray-800/25" style="z-index: 1;"></div>
            <!-- Additional overlay for better text contrast -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent" style="z-index: 1;"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 mx-auto flex max-w-7xl flex-col justify-center px-3 sm:px-4 md:px-6 lg:px-8 py-8 sm:py-12 md:py-16 lg:py-20 text-center w-full">
            <!-- Badge -->
            <div class="animate-fade-in-up mb-6">
                <span class="inline-flex items-center gap-2 rounded-full bg-orange-500/20 px-4 py-2 text-sm font-semibold uppercase tracking-widest text-orange-200 backdrop-blur-sm">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    Porto Alegre
                </span>
            </div>

            <!-- Main Heading -->
            <h1 class="animate-fade-in-up animation-delay-200 mb-3 sm:mb-4 md:mb-6 text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl 2xl:text-7xl font-extrabold leading-tight text-white drop-shadow-2xl px-2 break-words">
                @if(isset($heroSection) && $heroSection && !empty($heroSection->title))
                    {!! $heroSection->title !!}
                @else
                    Capital Mundial do
                    <span class="block sm:inline bg-gradient-to-r from-orange-400 via-orange-300 to-orange-500 bg-clip-text text-transparent">Churrasco</span>
                @endif
            </h1>

            <!-- Subtitle -->
            <p class="animate-fade-in-up animation-delay-400 mx-auto mb-4 sm:mb-6 md:mb-8 lg:mb-10 max-w-3xl text-sm sm:text-base md:text-lg lg:text-xl leading-relaxed text-gray-100 drop-shadow-lg px-3 sm:px-4">
                @if(isset($heroSection) && $heroSection && !empty($heroSection->subtitle))
                    {!! $heroSection->subtitle !!}
                @else
                    Descubra as melhores experiências de churrasco. Explore estabelecimentos verificados, produtos especiais, promoções exclusivas e serviços artesanais da nossa comunidade.
                @endif
            </p>
            
            @if(isset($heroSection) && $heroSection && (!empty($heroSection->primary_button_text) || !empty($heroSection->secondary_button_text)))
                <!-- Hero Buttons -->
                <div class="animate-fade-in-up animation-delay-600 flex flex-col sm:flex-row gap-4 justify-center items-center mb-6 sm:mb-8">
                    @if(!empty($heroSection->primary_button_text))
                        <a href="{{ !empty($heroSection->primary_button_link) ? $heroSection->primary_button_link : '#' }}" class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl font-semibold shadow-lg hover:from-orange-600 hover:to-orange-700 hover:shadow-xl transition-all">
                            {{ $heroSection->primary_button_text }}
                        </a>
                    @endif
                    @if(!empty($heroSection->secondary_button_text))
                        <a href="{{ !empty($heroSection->secondary_button_link) ? $heroSection->secondary_button_link : '#' }}" class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-white/20 backdrop-blur-md text-white rounded-xl font-semibold border border-white/30 hover:bg-white/30 transition-all">
                            {{ $heroSection->secondary_button_text }}
                        </a>
                    @endif
                </div>
            @endif

            <!-- Search Form -->
            <form action="{{ route('search') }}" method="GET" class="animate-fade-in-up animation-delay-600 mx-auto mb-6 sm:mb-8 md:mb-12 lg:mb-16 w-full max-w-3xl px-3 sm:px-4" role="search" aria-label="Buscar estabelecimentos e produtos">
                <label for="homepage-search" class="sr-only">Buscar estabelecimentos, produtos e serviços</label>
                <div class="flex flex-col gap-3 rounded-2xl bg-white/15 p-2 backdrop-blur-md backdrop-saturate-150 lg:flex-row lg:items-center lg:shadow-2xl">
                    <div class="relative flex-1">
                        <label for="homepage-search" class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 pointer-events-none" aria-hidden="true">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </label>
                    <input
                        id="homepage-search"
                        name="q"
                            type="search"
                        value="{{ request('q') }}"
                            placeholder="Buscar churrascarias, açougues..."
                            class="w-full rounded-xl border border-white/20 bg-white/20 pl-10 sm:pl-12 pr-4 sm:pr-6 py-3 sm:py-4 text-sm sm:text-base text-white placeholder-gray-200 outline-none backdrop-blur-sm transition-all focus:border-orange-400 focus:bg-white/25 focus:ring-2 focus:ring-orange-400/50"
                            autocomplete="off"
                            aria-describedby="search-description"
                        >
                        <span id="search-description" class="sr-only">Digite sua busca e pressione Enter ou clique no botão Buscar</span>
                    </div>
                    <button
                        type="submit"
                        class="group rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 px-6 sm:px-8 py-3 sm:py-4 text-sm sm:text-base font-semibold text-white shadow-lg transition-all hover:from-orange-600 hover:to-orange-700 hover:shadow-xl hover:scale-105 active:scale-100 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 focus:ring-offset-transparent w-full lg:w-auto"
                        aria-label="Executar busca"
                    >
                        <span class="flex items-center justify-center gap-2">
                        Buscar
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </span>
                    </button>
                </div>
            </form>

            <!-- Metrics Cards -->
            <dl class="animate-fade-in-up animation-delay-800 grid gap-3 sm:gap-4 md:gap-6 text-left grid-cols-1 sm:grid-cols-3 px-2 sm:px-4" role="list">
                <div class="group rounded-2xl bg-white/10 p-4 sm:p-6 backdrop-blur-md backdrop-saturate-150 transition-all hover:bg-white/15 hover:scale-105 hover:shadow-xl" role="listitem">
                    <dt class="mb-2 flex items-center gap-2 text-xs sm:text-sm font-medium text-orange-200">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                        </svg>
                        Estabelecimentos verificados
                    </dt>
                    <dd class="text-2xl sm:text-3xl md:text-4xl font-bold text-white" aria-label="{{ number_format($metrics['establishments_count'] ?? 0) }} estabelecimentos verificados">
                        {{ number_format($metrics['establishments_count'] ?? 0) }}
                    </dd>
                </div>
                <div class="group rounded-2xl bg-white/10 p-4 sm:p-6 backdrop-blur-md backdrop-saturate-150 transition-all hover:bg-white/15 hover:scale-105 hover:shadow-xl" role="listitem">
                    <dt class="mb-2 flex items-center gap-2 text-xs sm:text-sm font-medium text-orange-200">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        Avaliação da comunidade
                    </dt>
                    <dd class="text-2xl sm:text-3xl md:text-4xl font-bold text-white" aria-label="Avaliação média de {{ number_format($metrics['top_rating'] ?? 0, 1, ',', '.') }} estrelas">
                        {{ number_format($metrics['top_rating'] ?? 0, 1, ',', '.') }}
                    </dd>
                </div>
                <div class="group rounded-2xl bg-white/10 p-4 sm:p-6 backdrop-blur-md backdrop-saturate-150 transition-all hover:bg-white/15 hover:scale-105 hover:shadow-xl" role="listitem">
                    <dt class="mb-2 flex items-center gap-2 text-xs sm:text-sm font-medium text-orange-200">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                        </svg>
                        Produtos no marketplace
                    </dt>
                    <dd class="text-2xl sm:text-3xl md:text-4xl font-bold text-white" aria-label="{{ number_format($metrics['products_count'] ?? 0) }} produtos disponíveis no marketplace">
                        {{ number_format($metrics['products_count'] ?? 0) }}
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <svg class="h-8 w-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>
    </section>

    <section class="bg-white py-8 sm:py-12 md:py-16">
        <div class="mx-auto max-w-6xl px-3 sm:px-4 md:px-6">
            <div class="flex flex-col items-start justify-between gap-4 sm:gap-6 md:flex-row md:items-center">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Churrascarias em destaque</h2>
                    <p class="mt-2 text-sm sm:text-base text-gray-600">
                        Dados verificados, avaliações reais e experiências curadas diretamente dos nossos estabelecimentos parceiros.
                    </p>
                </div>
                <a
                    href="{{ route('mapa') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-3 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-gray-700 transition hover:border-orange-400 hover:text-orange-500 whitespace-nowrap"
                >
                    Ver mapa interativo
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="mt-6 sm:mt-8 md:mt-10 grid gap-4 sm:gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($featuredEstablishments as $establishment)
                    <article class="flex h-full flex-col overflow-hidden rounded-2xl border border-gray-100 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="relative h-48 bg-gradient-to-br from-orange-100 to-red-100">
                            @php
                                $photo = $establishment->photo_urls[0] ?? null;
                            @endphp
                            @if ($photo)
                                <img
                                    src="{{ $photo }}"
                                    alt="{{ $establishment->name }}"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                >
                            @else
                                <div class="flex h-full w-full items-center justify-center text-5xl">🥩</div>
                            @endif
                            <div class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-orange-600">
                                {{ ucfirst($establishment->category ?? 'churrasco') }}
                            </div>
                        </div>
                        <div class="flex flex-1 flex-col p-6">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $establishment->name }}</h3>
                            <p class="mt-2 text-sm text-gray-600">
                                {{ Str::limit($establishment->description ?? 'Experiência premium de churrasco curada pela comunidade POA.', 120) }}
                            </p>
                            <dl class="mt-4 flex flex-wrap gap-4 text-sm text-gray-500">
                                <div class="flex items-center gap-2">
                                    <svg class="h-4 w-4 text-orange-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span>{{ number_format($establishment->rating ?? 0, 1, ',', '.') }} avaliação</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    <span>{{ $establishment->formatted_address ?? $establishment->address ?? 'Endereço em breve' }}</span>
                                </div>
                            </dl>
                            <div class="mt-auto pt-6">
                                <a
                                    href="{{ route('public.establishment', $establishment) }}"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-orange-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-orange-600"
                                >
                                    Ver detalhes
                                    <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-12 text-center text-gray-500">
                        Publique estabelecimentos em destaque no painel para preencher esta seção automaticamente.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="bg-gray-50 py-8 sm:py-12 md:py-16">
        <div class="mx-auto max-w-6xl px-3 sm:px-4 md:px-6">
            <div class="flex flex-col items-start justify-between gap-4 sm:gap-6 md:flex-row md:items-center">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Destaques do marketplace</h2>
                    <p class="mt-2 text-sm sm:text-base text-gray-600">
                        Produtos respeitam disponibilidade, preços e regras de destaque configuradas no painel.
                    </p>
                </div>
                <a
                    href="{{ route('products') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-3 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-gray-700 transition hover:border-orange-400 hover:text-orange-500 whitespace-nowrap"
                >
                    Explorar marketplace
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="mt-6 sm:mt-8 md:mt-10 grid gap-4 sm:gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($highlightProducts as $product)
                    <article class="flex h-full flex-col rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow">
                        <div class="relative h-40 bg-gradient-to-br from-orange-100 to-red-100">
                            @php
                                $image = $product->images[0] ?? null;
                            @endphp
                            @if ($image)
                                <img
                                    src="{{ Storage::disk('public')->url($image) }}"
                                    alt="{{ $product->name }}"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                >
                            @else
                                <div class="flex h-full w-full items-center justify-center text-4xl">🔥</div>
                            @endif
                            @if ($product->is_featured)
                                <div class="absolute left-4 top-4 rounded-full bg-orange-500 px-3 py-1 text-xs font-semibold text-white">
                                    Destaque
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                            <p class="mt-2 text-sm text-gray-600">
                                {{ Str::limit($product->description ?? 'Produto premium de churrasco curado para experiências inesquecíveis.', 90) }}
                            </p>
                            <dl class="mt-4 space-y-2 text-sm text-gray-500">
                                <div class="flex items-center justify-between">
                                    <span>Preço</span>
                                    <span class="font-semibold text-gray-900">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                                </div>
                                @if ($product->rating > 0)
                                    <div class="flex items-center justify-between">
                                        <span>Avaliação</span>
                                        <span>{{ number_format($product->rating, 1, ',', '.') }} ({{ $product->review_count }})</span>
                                    </div>
                                @endif
                                @if ($product->establishment)
                                    <div class="flex items-center justify-between">
                                        <span>Estabelecimento</span>
                                        <a
                                            href="{{ route('public.establishment', $product->establishment) }}"
                                            class="font-semibold text-orange-600 hover:underline"
                                        >
                                            {{ $product->establishment->name }}
                                        </a>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </article>
                @empty
                    <div class="md:col-span-2 lg:col-span-4 rounded-2xl border border-dashed border-gray-200 bg-white p-12 text-center text-gray-500">
                        Produtos do marketplace aparecerão aqui assim que o painel publicar itens ativos.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-6xl px-6">
            <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Promoções frescas</h2>
                    <p class="mt-2 text-gray-600">
                        Descontos e ofertas especiais curadas de campanhas do painel com datas de início e fim.
                    </p>
                </div>
                <a
                    href="{{ route('promotions') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:border-orange-400 hover:text-orange-500"
                >
                    Ver todas as promoções
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($highlightPromotions as $promotion)
                    <article class="flex h-full flex-col rounded-2xl border border-gray-100 bg-gradient-to-br from-orange-50 to-red-50 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wide text-orange-600">
                            <span>{{ strtoupper($promotion->promotion_type) }}</span>
                            @if ($promotion->is_featured)
                                <span class="rounded-full bg-orange-500 px-2 py-0.5 text-white">Destaque</span>
                            @endif
                        </div>
                        <h3 class="mt-4 text-xl font-semibold text-gray-900">{{ $promotion->title }}</h3>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ Str::limit($promotion->description ?? 'Promoção por tempo limitado para a comunidade do churrasco.', 110) }}
                        </p>
                        <dl class="mt-4 space-y-2 text-sm text-gray-700">
                            <div class="flex items-center justify-between">
                                <span>Desconto</span>
                                <span class="font-semibold text-gray-900">
                                    @if ($promotion->promotion_type === 'percentage')
                                        {{ number_format($promotion->discount_value, 0) }}%
                                    @else
                                        R$ {{ number_format($promotion->discount_value, 2, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                            @if ($promotion->starts_at || $promotion->ends_at)
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>Válido</span>
                                    <span>{{ optional($promotion->starts_at)->format('d/m') ?? 'Agora' }} - {{ optional($promotion->ends_at)->format('d/m') ?? 'Aberto' }}</span>
                                </div>
                            @endif
                        </dl>
                        @if ($promotion->establishment)
                            <div class="mt-6 text-sm">
                                <span class="text-gray-500">Oferecido por</span>
                                <a href="{{ route('public.establishment', $promotion->establishment) }}" class="ml-2 font-semibold text-orange-600 hover:underline">
                                    {{ $promotion->establishment->name }}
                                </a>
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="md:col-span-2 lg:col-span-3 rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-12 text-center text-gray-500">
                        Publique promoções em destaque no painel para destacá-las aqui.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="bg-gray-50 py-16">
        <div class="mx-auto max-w-6xl px-6">
            <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Serviços exclusivos</h2>
                    <p class="mt-2 text-gray-600">
                        Reserve experiências de churrasco sob medida, catering e consultoria curados pelos estabelecimentos.
                    </p>
                </div>
                <a
                    href="{{ route('services.public') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:border-orange-400 hover:text-orange-500"
                >
                    Explorar serviços
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($highlightServices as $service)
                    <article class="flex h-full flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow">
                        <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wide text-orange-600">
                            <span>{{ ucfirst(str_replace('_', ' ', $service->category)) }}</span>
                            @if ($service->is_featured)
                                <span class="rounded-full bg-orange-500 px-2 py-0.5 text-white">Destaque</span>
                            @endif
                        </div>
                        <h3 class="mt-4 text-xl font-semibold text-gray-900">{{ $service->name }}</h3>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ Str::limit($service->description ?? 'Serviço completo de churrasco com cortes selecionados, equipe e equipamentos.', 110) }}
                        </p>
                        <dl class="mt-4 space-y-2 text-sm text-gray-700">
                            <div class="flex items-center justify-between">
                                <span>Preço</span>
                                <span class="font-semibold text-gray-900">R$ {{ number_format($service->price, 2, ',', '.') }}</span>
                            </div>
                            @if ($service->capacity)
                                <div class="flex items-center justify-between">
                                    <span>Capacidade</span>
                                    <span>{{ $service->capacity }} convidados</span>
                                </div>
                            @endif
                        </dl>
                        @if ($service->establishment)
                            <div class="mt-6 text-sm">
                                <span class="text-gray-500">Fornecido por</span>
                                <a href="{{ route('public.establishment', $service->establishment) }}" class="ml-2 font-semibold text-orange-600 hover:underline">
                                    {{ $service->establishment->name }}
                                </a>
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="md:col-span-2 lg:col-span-3 rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-12 text-center text-gray-500">
                        Publique serviços em destaque no painel para destacá-los aqui.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Tourism Quality Seal Section -->
    <section class="bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 py-16 sm:py-20 md:py-24 relative overflow-hidden">
        <!-- Decorative background elements -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-orange-200/30 to-amber-200/30 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-blue-200/30 to-orange-200/30 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
        
        <div class="relative max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12 md:mb-16">
                <div class="inline-flex items-center justify-center mb-6">
                    <div class="relative">
                        <!-- Premium Seal Badge -->
                        <div class="w-32 h-32 sm:w-40 sm:h-40 md:w-48 md:h-48 bg-gradient-to-br from-orange-500 via-amber-500 to-orange-600 rounded-full flex items-center justify-center shadow-2xl border-8 border-white/90 relative transform rotate-12 hover:rotate-0 transition-transform duration-500">
                            <svg class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg animate-pulse">
                                <svg class="w-5 h-5 text-yellow-900" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <span class="inline-flex items-center gap-2 rounded-full bg-orange-500/10 px-4 py-2 text-sm font-semibold uppercase tracking-widest text-orange-700 border border-orange-300/50">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Selo de Qualidade
                    </span>
                </div>
                
                <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
                    Selo de Qualidade da<br/>Secretaria de Turismo
                </h2>
                <p class="text-lg sm:text-xl md:text-2xl text-gray-700 max-w-4xl mx-auto leading-relaxed mb-8">
                    Reconhecimento de excelência concedido aos estabelecimentos que representam o melhor da tradição gastronômica e hospitalidade de Porto Alegre
                </p>
                
                <!-- Features -->
                <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6 max-w-4xl mx-auto mt-12">
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-orange-200/50">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-500 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2 text-center">Alto Padrão</h3>
                        <p class="text-sm text-gray-600 text-center">Reconhecimento de qualidade excepcional em produtos e serviços</p>
                    </div>
                    
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-orange-200/50">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-orange-500 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2 text-center">Reconhecimento Oficial</h3>
                        <p class="text-sm text-gray-600 text-center">Avaliação e aprovação da Secretaria de Turismo de Porto Alegre</p>
                    </div>
                    
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-orange-200/50 sm:col-span-2 md:col-span-1">
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2 text-center">Excelência Garantida</h3>
                        <p class="text-sm text-gray-600 text-center">Selos concedidos por mérito ou votação da comunidade</p>
                    </div>
                </div>
            </div>

            <!-- Quality Seal Establishments -->
            @if(isset($qualitySealEstablishments) && $qualitySealEstablishments->count() > 0)
                <div class="mt-16">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Estabelecimentos Certificados</h3>
                            <p class="text-gray-600">Conheça os estabelecimentos que receberam o Selo de Qualidade</p>
                        </div>
                    </div>

                    <div class="grid gap-6 sm:gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($qualitySealEstablishments as $establishment)
                            <article class="group relative flex h-full flex-col overflow-hidden rounded-2xl border-2 border-orange-200 bg-white shadow-xl transition-all hover:-translate-y-2 hover:shadow-2xl hover:border-orange-400">
                                <!-- Quality Seal Badge -->
                                <div class="absolute top-4 right-4 z-10">
                                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 via-amber-500 to-orange-600 rounded-full flex items-center justify-center shadow-lg border-4 border-white transform group-hover:scale-110 transition-transform">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                        </svg>
                                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-yellow-400 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3 text-yellow-900" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative h-48 bg-gradient-to-br from-orange-100 to-red-100">
                                    @php
                                        $photo = $establishment->photo_urls[0] ?? null;
                                    @endphp
                                    @if ($photo)
                                        <img
                                            src="{{ $photo }}"
                                            alt="{{ $establishment->name }}"
                                            class="h-full w-full object-cover"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-5xl">🥩</div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                                    <div class="absolute bottom-4 left-4 right-4">
                                        <div class="inline-flex items-center gap-2 rounded-full bg-orange-500/90 backdrop-blur-sm px-3 py-1.5 text-xs font-semibold text-white">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Selo de Qualidade
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex flex-1 flex-col p-6">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $establishment->name }}</h3>
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                        {{ Str::limit($establishment->description ?? 'Estabelecimento certificado com Selo de Qualidade da Secretaria de Turismo.', 120) }}
                                    </p>
                                    
                                    <dl class="mt-auto space-y-2 text-sm text-gray-500">
                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-orange-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span class="font-semibold">{{ number_format($establishment->rating ?? 0, 1, ',', '.') }}</span>
                                            <span>avaliação</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                            <span>{{ $establishment->formatted_address ?? $establishment->address ?? 'Porto Alegre, RS' }}</span>
                                        </div>
                                        @if($establishment->tourism_quality_seal_date)
                                            <div class="flex items-center gap-2 text-xs text-orange-600 font-medium">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Certificado em {{ $establishment->tourism_quality_seal_date->format('d/m/Y') }}
                                            </div>
                                        @endif
                                    </dl>
                                    
                                    <div class="mt-6 pt-6 border-t border-gray-100">
                                        <a
                                            href="{{ route('public.establishment', $establishment) }}"
                                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 px-4 py-3 text-sm font-semibold text-white transition-all hover:from-orange-600 hover:to-orange-700 hover:shadow-lg"
                                        >
                                            Ver Estabelecimento
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="mt-12 text-center">
                    <div class="inline-block bg-white/80 backdrop-blur-sm rounded-2xl p-8 shadow-lg border border-orange-200/50">
                        <svg class="w-16 h-16 text-orange-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-600 text-lg">
                            Estabelecimentos certificados aparecerão aqui em breve.
                        </p>
                    </div>
                </div>
            @endif

            <!-- CTA Section -->
            <div class="mt-16 text-center">
                <div class="bg-gradient-to-r from-orange-500 to-amber-500 rounded-2xl p-8 md:p-12 shadow-2xl">
                    <h3 class="text-2xl sm:text-3xl font-bold text-white mb-4">
                        Seja Reconhecido com o Selo de Qualidade
                    </h3>
                    <p class="text-orange-50 text-lg mb-6 max-w-2xl mx-auto">
                        O Selo de Qualidade da Secretaria de Turismo reconhece estabelecimentos que se destacam pela excelência em produtos, serviços e tradição gastronômica.
                    </p>
                    <a href="{{ url('/secretaria-turismo') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-orange-600 rounded-xl font-semibold text-lg hover:bg-orange-50 transition-all shadow-lg hover:shadow-xl">
                        Saiba Mais
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Tourism Secretariat Section -->
    <section class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 text-white py-16 sm:py-20 md:py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-center">
                <div>
                    <div class="mb-6">
                        <span class="inline-flex items-center gap-2 rounded-full bg-blue-500/20 px-4 py-2 text-sm font-semibold uppercase tracking-widest text-blue-200 backdrop-blur-sm">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            Parceria Oficial
                        </span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-6 leading-tight">
                        Secretaria de Turismo de Porto Alegre
                    </h2>
                    <p class="text-lg sm:text-xl text-blue-100 leading-relaxed mb-8">
                        Esta iniciativa é uma parceria da Secretaria de Turismo de Porto Alegre com a Expo Churrasco para promover e fortalecer o turismo na capital gaúcha, destacando nossa rica tradição gastronômica e cultural.
                    </p>
                    <div class="space-y-4 mb-8">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-orange-400 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h3 class="font-semibold text-lg mb-1">Promoção Turística</h3>
                                <p class="text-blue-100">Desenvolvimento de estratégias para posicionar Porto Alegre como destino turístico de excelência</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-orange-400 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h3 class="font-semibold text-lg mb-1">Fortalecimento Econômico</h3>
                                <p class="text-blue-100">Apoio ao setor turístico local gerando oportunidades de negócios e desenvolvimento</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-orange-400 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <h3 class="font-semibold text-lg mb-1">Preservação Cultural</h3>
                                <p class="text-blue-100">Valorização das tradições gaúchas e do churrasco como expressão cultural única</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ url('/secretaria-turismo') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl font-semibold text-lg hover:from-orange-600 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl">
                        Conheça Mais Sobre a Secretaria
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
                <div class="mt-8 md:mt-0">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 border border-white/20">
                        <h3 class="text-2xl font-bold mb-6">Capital Mundial do Churrasco</h3>
                        <p class="text-blue-100 leading-relaxed mb-6">
                            A parceria entre a Secretaria de Turismo e a Expo Churrasco reconhece Porto Alegre como a Capital Mundial do Churrasco, promovendo esta tradição centenária como um dos principais atrativos turísticos da cidade.
                        </p>
                        <div class="space-y-3">
                            <div class="flex items-center text-blue-100">
                                <svg class="w-5 h-5 text-orange-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                Tradição centenária preservada
                            </div>
                            <div class="flex items-center text-blue-100">
                                <svg class="w-5 h-5 text-orange-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                Experiências gastronômicas únicas
                            </div>
                            <div class="flex items-center text-blue-100">
                                <svg class="w-5 h-5 text-orange-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                Apoio ao setor turístico local
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    // Parallax effect for hero section
    (function() {
        'use strict';
        
        const heroSection = document.querySelector('.hero-background');
        if (!heroSection) return;
        
        // Check for reduced motion preference
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        let ticking = false;
        
        function updateParallax() {
            if (prefersReducedMotion) {
                ticking = false;
                return;
            }
            
            const scrolled = window.pageYOffset;
            const heroHeight = heroSection.offsetHeight;
            const heroTop = heroSection.getBoundingClientRect().top + scrolled;
            const windowHeight = window.innerHeight;
            
            // Only apply parallax when hero is visible
            if (scrolled + windowHeight > heroTop && scrolled < heroTop + heroHeight) {
                const parallaxSpeed = 0.5;
                const yPos = -(scrolled - heroTop) * parallaxSpeed;
                heroSection.style.transform = `translate3d(0, ${yPos}px, 0)`;
            }
            
            ticking = false;
        }
        
        function requestTick() {
            if (!ticking) {
                window.requestAnimationFrame(updateParallax);
                ticking = true;
            }
        }
        
        // Throttle scroll events for better performance
        if (!prefersReducedMotion) {
            window.addEventListener('scroll', requestTick, { passive: true });
            // Initial call
            updateParallax();
        }
        
        // Hide skeleton when content loads
        function hideSkeleton() {
            const skeleton = document.querySelector('.hero-skeleton');
            if (skeleton) {
                skeleton.classList.add('hidden');
                // Remove from DOM after animation
                setTimeout(() => {
                    if (skeleton.parentNode) {
                        skeleton.remove();
                    }
                }, 500);
            }
        }
        
        // Handle image loading for progressive enhancement
        const heroImage = heroSection.querySelector('.hero-image');
        if (heroImage) {
            function markImageLoaded() {
                heroImage.classList.add('loaded');
                heroImage.style.opacity = '1';
                hideSkeleton();
            }
            
            heroImage.addEventListener('load', markImageLoaded);
            heroImage.addEventListener('error', function() {
                // Even on error, hide skeleton
                hideSkeleton();
                console.warn('Hero image failed to load');
            });
            
            // Fallback if image is already loaded
            if (heroImage.complete && heroImage.naturalHeight !== 0) {
                markImageLoaded();
            } else {
                // Fallback timeout in case load event doesn't fire
                setTimeout(markImageLoaded, 2000);
            }
        }
        
        // Handle video loading
        const heroVideo = heroSection.querySelector('.hero-video');
        if (heroVideo) {
            function markVideoLoaded() {
                heroVideo.classList.add('loaded');
                heroVideo.style.opacity = '1';
                hideSkeleton();
            }
            
            heroVideo.addEventListener('loadeddata', markVideoLoaded);
            heroVideo.addEventListener('canplay', markVideoLoaded);
            heroVideo.addEventListener('error', function() {
                hideSkeleton();
                console.warn('Hero video failed to load');
            });
            
            // Ensure video plays (some browsers require user interaction)
            const playPromise = heroVideo.play();
            if (playPromise !== undefined) {
                playPromise
                    .then(() => {
                        // Video is playing
                        markVideoLoaded();
                    })
                    .catch(function(error) {
                        console.log('Video autoplay prevented:', error);
                        // Still hide skeleton even if autoplay fails
                        markVideoLoaded();
                    });
            }
        }
        
        // Fallback: hide skeleton after max wait time
        setTimeout(hideSkeleton, 3000);
    })();
    
    // Hero slideshow functionality
    (function() {
        'use strict';
        
        const slideshow = document.querySelector('.hero-slideshow');
        if (!slideshow) return;
        
        const slides = slideshow.querySelectorAll('.hero-slide');
        if (slides.length <= 1) return;
        
        let currentSlide = 0;
        const slideInterval = 5000; // 5 seconds
        
        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.display = i === index ? 'block' : 'none';
            });
        }
        
        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }
        
        // Start slideshow
        const slideshowInterval = setInterval(nextSlide, slideInterval);
        
        // Pause on hover
        slideshow.addEventListener('mouseenter', () => {
            clearInterval(slideshowInterval);
        });
        
        slideshow.addEventListener('mouseleave', () => {
            const newInterval = setInterval(nextSlide, slideInterval);
            // Store interval ID for cleanup if needed
        });
    })();
</script>
@endpush
@endsection
