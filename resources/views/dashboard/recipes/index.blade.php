@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Recipes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Recipes</h1>
            <p class="text-gray-600 mt-2">Share signature dishes and grill techniques with your audience.</p>
        </div>
        <a href="{{ route('recipes.create') }}" class="inline-flex items-center px-5 py-3 bg-churrasco-600 text-white rounded-xl shadow hover:bg-churrasco-700 transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Recipe
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl mb-8">
        <form method="GET" class="p-6 grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" id="search" name="search" value="{{ $filters['search'] ?? '' }}"
                       class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500"
                       placeholder="Recipe title or description">
            </div>
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="category" name="category"
                        class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    <option value="">All</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>
                            {{ ucfirst($category) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="difficulty" class="block text-sm font-medium text-gray-700">Difficulty</label>
                <select id="difficulty" name="difficulty"
                        class="mt-2 block w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-churrasco-500 focus:border-churrasco-500">
                    <option value="">All</option>
                    @foreach($difficulties as $difficulty)
                        <option value="{{ $difficulty }}" @selected(($filters['difficulty'] ?? '') === $difficulty)>
                            {{ ucfirst($difficulty) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end space-x-3">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="featured" value="1" @checked(!empty($filters['featured']))
                           class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                    <span class="ml-2 text-sm text-gray-700">Featured only</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="status" value="published" @checked(($filters['status'] ?? '') === 'published')
                           class="rounded border-gray-300 text-churrasco-600 focus:ring-churrasco-500">
                    <span class="ml-2 text-sm text-gray-700">Published only</span>
                </label>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-xl hover:bg-gray-700 transition-colors duration-200">
                    Apply
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Recipe</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Difficulty</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Servings</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Views</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recipes as $recipe)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-4">
                                @if(!empty($recipe->images))
                                    <img src="{{ Storage::disk('public')->url($recipe->images[0]) }}" alt="{{ $recipe->title }}" class="w-12 h-12 rounded-xl object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $recipe->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $recipe->establishment->name ?? 'Shared recipe' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ ucfirst($recipe->category) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ ucfirst($recipe->difficulty) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $recipe->servings }} servings
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $recipe->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $recipe->is_published ? 'Published' : 'Draft' }}
                                </span>
                                @if($recipe->is_featured)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        Featured
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-gray-600">
                            {{ $recipe->view_count }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center space-x-2">
                                <a href="{{ route('recipes.show', $recipe) }}" class="text-sm text-churrasco-600 hover:text-churrasco-700 font-semibold">View</a>
                                <a href="{{ route('recipes.edit', $recipe) }}" class="text-sm text-gray-600 hover:text-gray-800 font-semibold">Edit</a>
                                <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this recipe?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-semibold">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No recipes published yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $recipes->links() }}
    </div>
</div>
@endsection












