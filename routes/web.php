<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicSite\PublicSiteController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\BbqPortalController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SystemSettingsController;
use App\Models\User;

// Public routes
Route::get('/', [PublicSiteController::class, 'home'])->name('home');
Route::get('/mapa', [PublicSiteController::class, 'map'])->name('mapa');

// Public API for map data
Route::get('/api/establishments/map/data', [EstablishmentController::class, 'mapData'])->name('api.establishments.map.data');

// Proxy for establishment photos
Route::get('/establishment-photo/{id}/{index?}', [EstablishmentController::class, 'proxyPhoto'])->name('establishment.photo.proxy');

Route::get('/establishments/{slug}', [PublicSiteController::class, 'establishment'])
    ->name('public.establishment');
Route::get('/estabelecimento/{establishment}', function (\App\Models\Establishment $establishment) {
    return redirect()->route('public.establishment', $establishment);
});

Route::get('/buscar', [PublicSiteController::class, 'search'])->name('search');
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

Route::get('/produtos', [PublicSiteController::class, 'products'])->name('products');
Route::get('/promocoes', [PublicSiteController::class, 'promotions'])->name('promotions');
Route::get('/servicos', [PublicSiteController::class, 'services'])->name('services.public');

// Auth routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $remember = $request->boolean('remember');

    if (! Auth::attempt([
        'email' => $credentials['email'],
        'password' => $credentials['password'],
        'is_active' => true,
    ], $remember)) {
        throw ValidationException::withMessages([
            'email' => 'Invalid credentials provided.',
        ]);
    }

    $request->session()->regenerate();

    return redirect()->intended(route('dashboard'));
})->name('login.post')->middleware('guest');

Route::get('/register', function () {
    return view('auth.register');
})->name('register')->middleware('guest');

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'phone' => ['nullable', 'string', 'max:20'],
        'password' => ['required', 'confirmed', 'min:8'],
        'terms' => ['accepted'],
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'password' => $validated['password'],
        'role' => 'user',
        'is_active' => true,
    ]);

    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->route('dashboard');
})->name('register.post')->middleware('guest');

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

Route::post('/logout', [GoogleAuthController::class, 'logout'])->middleware('auth')->name('logout');

// Review routes
Route::middleware(['auth'])->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Favorite routes
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/check', [FavoriteController::class, 'check'])->name('favorites.check');
    
    // Order routes
    Route::get('/api/orders', [OrderController::class, 'index'])->name('api.orders.index');
    Route::post('/api/orders', [OrderController::class, 'store'])->name('api.orders.store');
    Route::get('/api/orders/{order}', [OrderController::class, 'show'])->name('api.orders.show');
    Route::post('/api/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('api.orders.cancel');
    Route::post('/api/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('api.orders.updateStatus');
});

// Public review routes (for displaying combined reviews)
Route::get('/api/establishments/{id}/reviews', [ReviewController::class, 'getCombinedReviews'])->name('api.establishments.reviews');
Route::post('/api/establishments/{id}/sync-reviews', [ReviewController::class, 'syncExternalReviews'])->name('api.establishments.sync.reviews');

// Protected routes
Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('establishments', EstablishmentController::class);
    Route::get('products/analytics', [ProductController::class, 'analytics'])->name('products.analytics');
    Route::resource('products', ProductController::class);
    Route::resource('promotions', PromotionController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('recipes', RecipeController::class);
    Route::resource('videos', VideoController::class);
    Route::resource('orders', OrderController::class);
    
    // Establishment API integration routes
    Route::post('/establishments/{establishment}/sync', [EstablishmentController::class, 'sync'])->name('establishments.sync');
    Route::get('/establishments/search/external', [EstablishmentController::class, 'searchExternal'])->name('establishments.search.external');
    Route::post('/establishments/import/external', [EstablishmentController::class, 'importExternal'])->name('establishments.import.external');
    Route::get('/establishments/map/data', [EstablishmentController::class, 'mapData'])->name('establishments.map.data');
    
    // System settings routes
    Route::get('/system-settings', [SystemSettingsController::class, 'index'])->name('system-settings');
    Route::post('/system-settings', [SystemSettingsController::class, 'update'])->name('system-settings.update');
    Route::get('/api/system-settings', [SystemSettingsController::class, 'getSettings'])->name('api.system-settings');
    
    // Add other dashboard routes here
});