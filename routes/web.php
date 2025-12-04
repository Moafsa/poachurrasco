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
use App\Http\Controllers\CartController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\SuperAdminController;
use App\Models\User;

// Public routes
Route::get('/secretaria-turismo', [PublicSiteController::class, 'tourismSecretariat'])->name('tourism.secretariat');
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
    try {
        // Try simple view first for testing
        if (request()->get('simple')) {
            return view('auth.login-simple');
        }
        return view('auth.login');
    } catch (\Throwable $e) {
        \Log::error('Login view error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        if (config('app.debug')) {
            return response('Error loading login page: ' . $e->getMessage() . '<br><br>File: ' . $e->getFile() . ':' . $e->getLine() . '<br><br><pre>' . $e->getTraceAsString() . '</pre>', 500);
        }
        return response('Error loading login page. Check logs for details.', 500);
    }
})->name('login')->middleware('guest');

Route::post('/login', function (Request $request) {
    \Log::info('Login POST request received', [
        'email' => $request->email,
        'has_csrf' => $request->has('_token'),
        'session_id' => $request->session()->getId(),
    ]);
    
    try {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        \Log::info('Credentials validated');

        $remember = $request->boolean('remember');

        \Log::info('Attempting auth', ['email' => $credentials['email']]);

        if (! Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'is_active' => true,
        ], $remember)) {
            \Log::warning('Auth attempt failed', ['email' => $credentials['email']]);
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials provided.',
            ]);
        }

        $user = Auth::user();
        \Log::info('Auth successful', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'is_admin' => $user->isAdmin(),
        ]);

        // Merge session cart to user cart if exists
        try {
            $oldSessionId = $request->session()->getId();
            \App\Models\Cart::mergeSessionCartToUser($oldSessionId, $user->id);
        } catch (\Exception $e) {
            \Log::warning('Failed to merge session cart', ['error' => $e->getMessage()]);
        }

        try {
            $request->session()->regenerate();
            \Log::info('Session regenerated', [
                'new_session_id' => $request->session()->getId(),
                'user_id_in_session' => $request->session()->get('login_web_' . sha1('Illuminate\Auth\Guard')),
            ]);
        } catch (\Throwable $e) {
            \Log::error('Session regenerate error: ' . $e->getMessage());
            throw $e;
        }

        try {
            $user = Auth::user();
            \Log::info('Redirecting user', ['role' => $user->role ?? 'none']);
            
            // Redirect admin users to super-admin dashboard
            if ($user && $user->isAdmin()) {
                \Log::info('Redirecting admin to super-admin dashboard');
                return redirect()->intended(route('super-admin.index'));
            }
            
            \Log::info('Redirecting to dashboard');
            return redirect()->intended(route('dashboard'));
        } catch (\Throwable $e) {
            \Log::error('Redirect error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            
            // Fallback: check if user is admin
            $user = Auth::user();
            if ($user && $user->isAdmin()) {
                return redirect('/super-admin');
            }
            
            return redirect('/dashboard');
        }
    } catch (ValidationException $e) {
        \Log::info('Validation exception thrown');
        throw $e;
    } catch (\Throwable $e) {
        \Log::error('Login POST error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        // Check if error is related to password hash
        $userEmail = $request->input('email', 'unknown');
        if (str_contains($e->getMessage(), 'Bcrypt algorithm') || str_contains($e->getMessage(), 'password does not use')) {
            \Log::warning('Password hash issue detected for user: ' . $userEmail);
            
            if (config('app.debug')) {
                return back()->withErrors([
                    'email' => 'Password format error. Please run: php artisan users:fix-passwords --email=' . $userEmail . ' --password=yourpassword'
                ])->withInput();
            }
            
            return back()->withErrors([
                'email' => 'There was a problem with your account. Please contact the administrator.'
            ])->withInput();
        }
        
        if (config('app.debug')) {
            return response('Error processing login: ' . $e->getMessage() . '<br><br>File: ' . $e->getFile() . ':' . $e->getLine() . '<br><br><pre>' . $e->getTraceAsString() . '</pre>', 500)->header('Content-Type', 'text/html');
        }
        return back()->withErrors(['email' => 'An error occurred. Please try again.'])->withInput();
    }
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
    
    // Merge session cart to user cart if exists
    try {
        $sessionId = $request->session()->getId();
        \App\Models\Cart::mergeSessionCartToUser($sessionId, $user->id);
    } catch (\Exception $e) {
        \Log::warning('Failed to merge session cart on register', ['error' => $e->getMessage()]);
    }
    
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

// Cart routes (public - no auth required, with rate limiting)
Route::prefix('api/cart')->name('api.cart.')->middleware(['throttle:60,1'])->group(function () {
    Route::get('/', [CartController::class, 'index'])->middleware('throttle:120,1')->name('index');
    Route::post('/add', [CartController::class, 'add'])->middleware('throttle:30,1')->name('add');
    Route::put('/update', [CartController::class, 'update'])->middleware('throttle:30,1')->name('update');
    Route::delete('/remove/{productId}', [CartController::class, 'remove'])->middleware('throttle:30,1')->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->middleware('throttle:10,1')->name('clear');
    Route::get('/count', [CartController::class, 'count'])->middleware('throttle:120,1')->name('count');
    Route::post('/calculate-totals', [CartController::class, 'calculateTotals'])->middleware('throttle:60,1')->name('calculate-totals');
});

// Cart and Checkout pages (public)
Route::get('/carrinho', function () {
    return view('cart.index');
})->name('cart.index');

Route::get('/checkout', function () {
    return view('checkout.index');
})->name('checkout.index')->middleware('auth');

Route::get('/pedido/{order}/confirmacao', function (\App\Models\Order $order) {
    // Check authorization
    if ($order->user_id !== auth()->id()) {
        abort(403);
    }
    $order->load(['establishment', 'user']);
    return view('checkout.confirmation', compact('order'));
})->name('checkout.confirmation')->middleware('auth');

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
    Route::get('/api/establishments/search', [EstablishmentController::class, 'search'])->name('api.establishments.search');
    
    // System settings routes
    Route::get('/system-settings', [SystemSettingsController::class, 'index'])->name('system-settings');
    Route::post('/system-settings', [SystemSettingsController::class, 'update'])->name('system-settings.update');
    Route::get('/api/system-settings', [SystemSettingsController::class, 'getSettings'])->name('api.system-settings');
    
    // Add other dashboard routes here
});

// Super Admin routes (protected by admin middleware)
Route::middleware(['auth', 'admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('index');
    
    // Site Content routes
    Route::get('/content', [SuperAdminController::class, 'content'])->name('content');
    Route::post('/content', [SuperAdminController::class, 'storeContent'])->name('content.store');
    Route::delete('/content/{content}', [SuperAdminController::class, 'deleteContent'])->name('content.delete');
    
    // Hero Section routes
    Route::get('/hero-sections', [SuperAdminController::class, 'heroSections'])->name('hero-sections');
    Route::get('/hero-section/create', [SuperAdminController::class, 'heroSectionForm'])->name('hero-section.create');
    Route::get('/hero-section/{heroSection}/edit', [SuperAdminController::class, 'heroSectionForm'])->name('hero-section.edit');
    Route::post('/hero-section', [SuperAdminController::class, 'storeHeroSection'])->name('hero-section.store');
    Route::put('/hero-section/{heroSection}', [SuperAdminController::class, 'storeHeroSection'])->name('hero-section.update');
    Route::delete('/hero-section/{heroSection}', [SuperAdminController::class, 'deleteHeroSection'])->name('hero-section.delete');
    
    // Hero Media routes
    Route::post('/hero-section/{heroSection}/upload-media', [SuperAdminController::class, 'uploadHeroMedia'])->name('hero-section.upload-media');
    Route::delete('/hero-section/media/{media}', [SuperAdminController::class, 'deleteHeroMedia'])->name('hero-section.delete-media');
    Route::post('/hero-section/{heroSection}/update-media-order', [SuperAdminController::class, 'updateHeroMediaOrder'])->name('hero-section.update-media-order');
});