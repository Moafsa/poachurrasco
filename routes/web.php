<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\BbqPortalController;

// Public routes
Route::get('/', function () {
    return view('public.home');
})->name('home');

Route::get('/mapa', function () {
    return view('public.mapa');
})->name('mapa');

// Public API for map data
Route::get('/api/establishments/map/data', [EstablishmentController::class, 'mapData'])->name('api.establishments.map.data');

// Proxy for establishment photos
Route::get('/establishment-photo/{id}', [EstablishmentController::class, 'proxyPhoto'])->name('establishment.photo.proxy');

Route::get('/estabelecimento/{id}', function ($id) {
    $establishment = App\Models\Establishment::findOrFail($id);
    return view('public.establishment-details', compact('establishment'));
})->name('establishment.details');

Route::get('/buscar', function () {
    return view('public.search');
})->name('search');

Route::get('/receitas', function () {
    return view('public.recipes');
})->name('recipes');

// Integrate BBQ Portal with existing recipes
Route::get('/receitas/guia/{guide}', [BbqPortalController::class, 'showGuide'])->name('recipes.guide.show');
Route::get('/receitas/chat', [BbqPortalController::class, 'chat'])->name('recipes.chat');
Route::post('/receitas/chat/message', [BbqPortalController::class, 'sendMessage'])->name('recipes.chat.message');
Route::get('/receitas/calculadora', [BbqPortalController::class, 'calculator'])->name('recipes.calculator');
Route::post('/receitas/calculadora/calcular', [BbqPortalController::class, 'calculate'])->name('recipes.calculator.calculate');
Route::get('/receitas/guias', [BbqPortalController::class, 'guides'])->name('recipes.guides');

Route::get('/produtos', function () {
    return view('public.products');
})->name('products');

// Auth routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', function () {
    // Simulate login for now
    session(['authenticated' => true, 'user_id' => 1, 'user_name' => 'Test User']);
    return redirect()->route('dashboard');
})->name('login.post');

Route::get('/register', function () {
    return view('auth.register');
})->name('register')->middleware('guest');

Route::post('/register', function () {
    // Simulate registration for now
    session(['authenticated' => true, 'user_id' => 1, 'user_name' => 'Test User']);
    return redirect()->route('dashboard');
})->name('register.post');

// Google OAuth routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Test route for Google OAuth configuration
Route::get('/test-google-oauth', function () {
    try {
        $redirectUrl = \Laravel\Socialite\Facades\Socialite::driver('google')->redirect()->getTargetUrl();
        $parsedUrl = parse_url($redirectUrl);
        parse_str($parsedUrl['query'] ?? '', $queryParams);
        
        return response()->json([
            'success' => true,
            'redirect_url' => $redirectUrl,
            'parsed_params' => $queryParams,
            'redirect_uri' => $queryParams['redirect_uri'] ?? 'NOT_FOUND',
            'config' => [
                'app_url' => config('app.url'),
                'google_redirect' => config('services.google.redirect'),
                'client_id' => config('services.google.client_id') ? 'SET' : 'NOT_SET',
                'client_secret' => config('services.google.client_secret') ? 'SET' : 'NOT_SET',
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'config' => [
                'app_url' => config('app.url'),
                'google_redirect' => config('services.google.redirect'),
                'client_id' => config('services.google.client_id') ? 'SET' : 'NOT_SET',
                'client_secret' => config('services.google.client_secret') ? 'SET' : 'NOT_SET',
            ]
        ]);
    }
})->name('test.google.oauth');

Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

// Review routes
Route::middleware(['auth'])->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Favorite routes
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/check', [FavoriteController::class, 'check'])->name('favorites.check');
});

// Public review routes (for displaying combined reviews)
Route::get('/api/establishments/{establishment}/reviews', [ReviewController::class, 'getCombinedReviews'])->name('api.establishments.reviews');
Route::post('/api/establishments/{establishment}/sync-reviews', [ReviewController::class, 'syncExternalReviews'])->name('api.establishments.sync.reviews');

// Protected routes
Route::middleware(['fakeauth'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('establishments', EstablishmentController::class);
    Route::resource('products', ProductController::class);
    
    // Establishment API integration routes
    Route::post('/establishments/{establishment}/sync', [EstablishmentController::class, 'sync'])->name('establishments.sync');
    Route::get('/establishments/search/external', [EstablishmentController::class, 'searchExternal'])->name('establishments.search.external');
    Route::post('/establishments/import/external', [EstablishmentController::class, 'importExternal'])->name('establishments.import.external');
    Route::get('/establishments/map/data', [EstablishmentController::class, 'mapData'])->name('establishments.map.data');
    
    // Add other dashboard routes here
});