@extends('layouts.app')

@section('title', 'POA Capital do Churrasco')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp
<div class="min-h-screen">
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-orange-900 to-gray-800 text-white">
        <div class="absolute inset-0 opacity-10" aria-hidden="true"
             style="background-image: radial-gradient(circle at top, rgba(251,146,60,0.6) 0%, transparent 60%);"></div>
        <div class="relative z-10 mx-auto max-w-6xl px-6 py-24 text-center">
            <span class="text-sm font-semibold uppercase tracking-widest text-orange-200">Porto Alegre</span>
            <h1 class="mt-4 text-4xl font-extrabold leading-tight md:text-6xl">
                Descubra as melhores experiências de churrasco na capital do churrasco
            </h1>
            <p class="mx-auto mt-6 max-w-3xl text-lg text-gray-200">
                Explore estabelecimentos verificados, produtos especiais, promoções exclusivas e serviços artesanais da plataforma POA Capital do Churrasco.
            </p>
            <form action="{{ route('search') }}" method="GET" class="mx-auto mt-10 max-w-3xl">
                <label for="homepage-search" class="sr-only">Buscar estabelecimentos e produtos</label>
                <div class="flex flex-col gap-3 rounded-2xl bg-white/10 p-2 backdrop-blur lg:flex-row lg:items-center">
                    <input
                        id="homepage-search"
                        name="q"
                        type="text"
                        value="{{ request('q') }}"
                        placeholder="Buscar churrascarias, açougues, produtos, serviços..."
                        class="flex-1 rounded-xl border border-white/10 bg-white/10 px-6 py-4 text-base text-white placeholder-gray-300 outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-400"
                    >
                    <button
                        type="submit"
                        class="rounded-xl bg-orange-500 px-8 py-4 text-base font-semibold text-white transition hover:bg-orange-600"
                    >
                        Buscar
                    </button>
                </div>
            </form>
            <dl class="mt-16 grid gap-6 text-left sm:grid-cols-3">
                <div class="rounded-2xl bg-white/10 p-6">
                    <dt class="text-sm font-medium text-orange-200">Estabelecimentos verificados</dt>
                    <dd class="mt-3 text-3xl font-semibold">
                        {{ number_format($metrics['establishments_count'] ?? 0) }}
                    </dd>
                </div>
                <div class="rounded-2xl bg-white/10 p-6">
                    <dt class="text-sm font-medium text-orange-200">Avaliação da comunidade</dt>
                    <dd class="mt-3 text-3xl font-semibold">
                        {{ number_format($metrics['top_rating'] ?? 0, 1, ',', '.') }}
                    </dd>
                </div>
                <div class="rounded-2xl bg-white/10 p-6">
                    <dt class="text-sm font-medium text-orange-200">Produtos no marketplace</dt>
                    <dd class="mt-3 text-3xl font-semibold">
                        {{ number_format($metrics['products_count'] ?? 0) }}
                    </dd>
                </div>
            </dl>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-6xl px-6">
            <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Churrascarias em destaque</h2>
                    <p class="mt-2 text-gray-600">
                        Dados verificados, avaliações reais e experiências curadas diretamente dos nossos estabelecimentos parceiros.
                    </p>
                </div>
                <a
                    href="{{ route('mapa') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:border-orange-400 hover:text-orange-500"
                >
                    Ver mapa interativo
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
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

    <section class="bg-gray-50 py-16">
        <div class="mx-auto max-w-6xl px-6">
            <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Destaques do marketplace</h2>
                    <p class="mt-2 text-gray-600">
                        Produtos respeitam disponibilidade, preços e regras de destaque configuradas no painel.
                    </p>
                </div>
                <a
                    href="{{ route('products') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:border-orange-400 hover:text-orange-500"
                >
                    Explorar marketplace
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
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
</div>
@endsection
