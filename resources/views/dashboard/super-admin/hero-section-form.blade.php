@extends('layouts.app')

@section('title', ($heroSection && $heroSection->exists) ? 'Edit Hero Section' : 'Create Hero Section')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-5xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
        <div class="mb-6">
            <a href="{{ route('super-admin.hero-sections') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Hero Sections
            </a>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900">{{ ($heroSection && $heroSection->exists) ? 'Edit' : 'Create' }} Hero Section</h1>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <form method="POST" action="{{ ($heroSection && $heroSection->exists) ? route('super-admin.hero-section.update', $heroSection) : route('super-admin.hero-section.store') }}">
                @csrf
                @if($heroSection && $heroSection->exists)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Page *</label>
                        <input type="text" name="page" value="{{ old('page', ($heroSection && $heroSection->exists) ? $heroSection->page : '') }}" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="home, about, contact">
                        <p class="text-xs text-gray-500 mt-1">Unique page identifier (e.g., 'home')</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="image" {{ old('type', ($heroSection && $heroSection->exists) ? $heroSection->type : '') === 'image' ? 'selected' : '' }}>Image</option>
                            <option value="video" {{ old('type', ($heroSection && $heroSection->exists) ? $heroSection->type : '') === 'video' ? 'selected' : '' }}>Video</option>
                            <option value="slideshow" {{ old('type', ($heroSection && $heroSection->exists) ? $heroSection->type : '') === 'slideshow' ? 'selected' : '' }}>Slideshow</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" name="title" value="{{ old('title', ($heroSection && $heroSection->exists) ? $heroSection->title : '') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                    <textarea name="subtitle" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('subtitle', ($heroSection && $heroSection->exists) ? $heroSection->subtitle : '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Primary Button Text</label>
                        <input type="text" name="primary_button_text" value="{{ old('primary_button_text', ($heroSection && $heroSection->exists) ? $heroSection->primary_button_text : '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Primary Button Link</label>
                        <input type="text" name="primary_button_link" value="{{ old('primary_button_link', ($heroSection && $heroSection->exists) ? $heroSection->primary_button_link : '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Button Text</label>
                        <input type="text" name="secondary_button_text" value="{{ old('secondary_button_text', ($heroSection && $heroSection->exists) ? $heroSection->secondary_button_text : '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Button Link</label>
                        <input type="text" name="secondary_button_link" value="{{ old('secondary_button_link', ($heroSection && $heroSection->exists) ? $heroSection->secondary_button_link : '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" id="is_active" 
                               {{ old('is_active', ($heroSection && $heroSection->exists) ? $heroSection->is_active : true) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Active</label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                        <input type="number" name="display_order" value="{{ old('display_order', ($heroSection && $heroSection->exists) ? $heroSection->display_order : 0) }}" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        {{ ($heroSection && $heroSection->exists) ? 'Update' : 'Create' }} Hero Section
                    </button>
                    <a href="{{ route('super-admin.hero-sections') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        @if($heroSection && $heroSection->exists)
            <!-- Media Upload Section -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Media Files</h2>
                
                <form method="POST" action="{{ route('super-admin.hero-section.upload-media', $heroSection) }}" enctype="multipart/form-data" class="mb-6" id="upload-media-form">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Media</label>
                        <div class="flex gap-4">
                            <input type="file" name="media[]" multiple accept="image/*,video/*" 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <select name="type" required class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="image">Image</option>
                                <option value="video">Video</option>
                            </select>
                            <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                Upload
                            </button>
                        </div>
                    </div>
                </form>

                @if($heroSection->media->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($heroSection->media->sortBy('display_order') as $media)
                            <div class="relative group">
                                @if($media->type === 'image')
                                    @php
                                        $disk = env('STORAGE_DISK', 'public');
                                        $imageUrl = \Illuminate\Support\Facades\Storage::disk($disk)->url($media->media_path);
                                        // Se a URL usar winio, corrigir para ws3
                                        if (str_contains($imageUrl, 'winio.conext.click')) {
                                            $imageUrl = str_replace('winio.conext.click', 'ws3.conext.click', $imageUrl);
                                        }
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="{{ $media->alt_text }}" class="w-full h-32 object-cover rounded-lg">
                                @else
                                    <div class="w-full h-32 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <form method="POST" action="{{ route('super-admin.hero-section.delete-media', $media) }}" onsubmit="return confirm('Delete this media?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                                <div class="absolute top-2 left-2 px-2 py-1 bg-black bg-opacity-50 text-white text-xs rounded">
                                    Order: {{ $media->display_order }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No media files uploaded yet</p>
                @endif
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Refresh CSRF token periodically to prevent 419 errors
    console.log('Hero Section CSRF Protection Script Loading...');
    
    (function() {
        'use strict';
        
        const forms = document.querySelectorAll('form[method="POST"]');
        if (forms.length === 0) {
            console.error('No POST forms found!');
            return;
        }
        console.log('Found', forms.length, 'POST form(s)');
        
        // Get first CSRF token as reference
        const firstForm = forms[0];
        let csrfToken = firstForm.querySelector('input[name="_token"]');
        if (!csrfToken) {
            console.error('CSRF token input not found!');
            return;
        }
        console.log('CSRF token found, initial value:', csrfToken.value.substring(0, 10) + '...');
        
        const currentUrl = window.location.href;
        let tokenUpdateInProgress = false;
        let lastUpdateTime = Date.now();
        
        // Function to update CSRF token in all forms
        function updateAllCsrfTokens(newTokenValue) {
            // Update meta tag if exists
            const metaToken = document.querySelector('meta[name="csrf-token"]');
            if (metaToken) {
                metaToken.setAttribute('content', newTokenValue);
            }
            
            // Update all CSRF tokens in all forms on the page
            document.querySelectorAll('input[name="_token"]').forEach(token => {
                token.value = newTokenValue;
            });
            
            console.log('All CSRF tokens updated to:', newTokenValue.substring(0, 10) + '...');
        }
        
        // Function to update CSRF token
        function updateCsrfToken() {
            if (tokenUpdateInProgress) {
                console.log('Token update already in progress, skipping...');
                return Promise.resolve();
            }
            
            tokenUpdateInProgress = true;
            console.log('Updating CSRF token...');
            
            return fetch(currentUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                },
                credentials: 'same-origin',
                cache: 'no-store'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.text();
            })
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newToken = doc.querySelector('input[name="_token"]');
                
                if (newToken && newToken.value) {
                    const currentTokenValue = csrfToken.value;
                    if (newToken.value !== currentTokenValue) {
                        updateAllCsrfTokens(newToken.value);
                        lastUpdateTime = Date.now();
                        console.log('CSRF token updated successfully at', new Date().toLocaleTimeString());
                    } else {
                        console.log('CSRF token is already up to date');
                    }
                } else {
                    console.warn('New CSRF token not found in response');
                }
            })
            .catch(err => {
                console.error('CSRF token refresh failed:', err);
            })
            .finally(() => {
                tokenUpdateInProgress = false;
            });
        }
        
        // Refresh token every 1 minute (more frequent to prevent expiration)
        console.log('Setting up periodic token refresh (every 60 seconds)');
        setInterval(function() {
            const timeSinceLastUpdate = Date.now() - lastUpdateTime;
            if (timeSinceLastUpdate > 50000) { // Update if more than 50 seconds since last update
                updateCsrfToken();
            }
        }, 60000);
        
        // Update token immediately on page load
        console.log('Updating token on page load...');
        updateCsrfToken();
        
        // Function to update token synchronously before form submission
        function updateTokenSync() {
            try {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', currentUrl, false); // false = synchronous
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('Accept', 'text/html');
                xhr.setRequestHeader('Cache-Control', 'no-cache');
                xhr.send();
                
                if (xhr.status === 200) {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(xhr.responseText, 'text/html');
                    const newToken = doc.querySelector('input[name="_token"]');
                    
                    if (newToken && newToken.value) {
                        updateAllCsrfTokens(newToken.value);
                        console.log('CSRF token updated synchronously before submission');
                        return true;
                    } else {
                        console.warn('Could not find new token in response');
                        return false;
                    }
                } else {
                    console.error('Failed to update token, status:', xhr.status);
                    return false;
                }
            } catch (err) {
                console.error('Error updating CSRF token before submission:', err);
                return false;
            }
        }
        
        // Update token before form submission for ALL forms
        // We use capture phase and update token WITHOUT preventing default
        // This ensures file uploads work correctly
        forms.forEach((form, index) => {
            const formId = form.id || `form-${index}`;
            console.log('Adding submit listener to form', index + 1, 'ID:', formId);
            
            // Use capture phase to run BEFORE any other handlers
            form.addEventListener('submit', function(e) {
                console.log('Form submission detected (form', index + 1, 'ID:', formId, '), updating CSRF token...');
                
                // Update token synchronously BEFORE allowing submit to continue
                // Don't prevent default - just update the token
                const tokenUpdated = updateTokenSync();
                
                if (!tokenUpdated) {
                    console.warn('Token update failed, but allowing submit to continue...');
                }
                
                // Let the form submit normally with the updated token
                console.log('Allowing form to submit with updated token...');
            }, true); // Use capture phase to ensure we run first
        });
        
        // Special handling for upload media form - ensure token is always fresh
        const uploadForm = document.getElementById('upload-media-form');
        if (uploadForm) {
            console.log('Found upload media form, adding special handler');
            uploadForm.addEventListener('submit', function(e) {
                console.log('Upload media form submission detected, ensuring fresh token...');
                // Update token before submit
                updateTokenSync();
            }, true);
        }
        
        console.log('Hero Section CSRF Protection Script Loaded Successfully!');
    })();
</script>
@endpush
@endsection
















