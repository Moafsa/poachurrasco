@extends('layouts.app')

@section('title', 'Search')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Search Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="mx-auto max-w-4xl px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Search establishments and products</h1>
            
            <!-- Search Form -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <form action="{{ route('search') }}" method="GET" class="space-y-6">
                    <!-- Main Search -->
                    <div class="relative">
                        <label for="search-query" class="sr-only">Search query</label>
                        <input 
                            type="text" 
                            id="search-query"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Search for establishments, products, addresses..." 
                            class="w-full px-6 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-lg"
                        >
                        <button type="submit" class="absolute right-2 top-2 bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-semibold transition-colors duration-200">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Results -->
    <div class="mx-auto max-w-6xl px-4 py-8">
        @if(request()->filled('q'))
            <!-- Results Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Search results</h2>
                    <p class="text-gray-600">{{ $results->count() }} result{{ $results->count() !== 1 ? 's' : '' }} found for "{{ request('q') }}"</p>
                </div>
            </div>

            <!-- Results Grid -->
            @if($results->count() > 0)
                <div class="grid lg:grid-cols-2 gap-6">
                    @foreach($results as $result)
                        <article class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                            <div class="flex">
                                <div class="w-32 h-32 bg-gradient-to-br from-orange-200 to-red-300 flex items-center justify-center flex-shrink-0">
                                    <div class="text-center text-white">
                                        <div class="text-3xl mb-1">
                                            @if($result['type'] === 'establishment')
                                                🔥
                                            @else
                                                🥩
                                            @endif
                                        </div>
                                        <div class="text-xs opacity-80 capitalize">{{ $result['type'] }}</div>
                                    </div>
                                </div>
                                <div class="flex-1 p-6">
                                    <div class="flex items-start justify-between mb-2">
                                        <h3 class="text-xl font-bold text-gray-900">{{ $result['title'] }}</h3>
                                        @if(isset($result['rating']) && $result['rating'] > 0)
                                            <div class="flex items-center text-orange-500">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                                <span class="font-semibold">{{ number_format($result['rating'], 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-gray-600 text-sm mb-3">{{ $result['description'] ?? 'No description available.' }}</p>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center text-gray-500 text-sm">
                                            @if($result['type'] === 'establishment' && isset($result['meta']['address']))
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                </svg>
                                                {{ Str::limit($result['meta']['address'], 40) }}
                                            @elseif($result['type'] === 'product' && isset($result['meta']['price']))
                                                <span class="font-semibold text-gray-900">R$ {{ number_format($result['meta']['price'], 2, ',', '.') }}</span>
                                            @endif
                                        </div>
                                        <a href="{{ $result['url'] }}" class="inline-flex items-center gap-2 rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-orange-600">
                                            View details
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No results found</h3>
                    <p class="text-gray-600 mb-6">We couldn't find any establishments or products matching "{{ request('q') }}".</p>
                    <a href="{{ route('search') }}" class="inline-flex items-center gap-2 rounded-lg bg-orange-500 px-6 py-3 text-base font-semibold text-white transition hover:bg-orange-600">
                        Try a new search
                    </a>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Start your search</h3>
                <p class="text-gray-600">Enter a search term above to find establishments, products, and more.</p>
            </div>
        @endif
    </div>
</div>
@endsection
