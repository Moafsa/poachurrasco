<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->validateCsrfTokens(except: [
            'login',
            'super-admin/branding/*',
        ]);
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle CSRF token expired (419 error)
        $exceptions->render(function (Illuminate\Session\TokenMismatchException $e, $request) {
            \Log::warning('CSRF token mismatch', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'has_session' => $request->hasSession(),
                'session_id' => $request->hasSession() ? $request->session()->getId() : 'no-session',
                'has_token' => $request->has('_token'),
                'token_value' => $request->has('_token') ? substr($request->input('_token'), 0, 10) . '...' : 'no-token',
            ]);
            
            // For login routes, regenerate session and redirect with error
            if ($request->is('login') || $request->is('login/*') || $request->routeIs('login.post')) {
                // Start a new session if needed
                if (!$request->hasSession()) {
                    $request->setLaravelSession(app('session')->driver());
                }
                
                // Regenerate session to get fresh CSRF token
                $request->session()->regenerate();
                
                return redirect()->route('login')
                    ->withErrors(['email' => 'Sessão expirada. Por favor, recarregue a página e tente fazer login novamente.'])
                    ->withInput($request->except('password', '_token'));
            }
            
            // For branding routes, regenerate session and redirect with error
            if ($request->is('super-admin/branding*')) {
                // Start a new session if needed
                if (!$request->hasSession()) {
                    $request->setLaravelSession(app('session')->driver());
                }
                
                // Regenerate session to get fresh CSRF token
                $request->session()->regenerate();
                
                return redirect()->route('super-admin.branding')
                    ->withErrors(['error' => 'Sessão expirada. Por favor, recarregue a página e tente novamente.']);
            }
            
            // For hero-section routes, regenerate session and redirect back with input data
            if ($request->is('super-admin/hero-section*')) {
                // Start a new session if needed
                if (!$request->hasSession()) {
                    $request->setLaravelSession(app('session')->driver());
                }
                
                // Regenerate session to get fresh CSRF token
                $request->session()->regenerate();
                
                // Determine redirect route based on request
                if ($request->routeIs('super-admin.hero-section.upload-media')) {
                    // For upload media route, redirect to edit page with the heroSection
                    $heroSectionId = $request->route('heroSection');
                    if ($heroSectionId) {
                        return redirect()->route('super-admin.hero-section.edit', $heroSectionId)
                            ->withErrors(['error' => 'Sessão expirada. Por favor, recarregue a página e tente fazer upload novamente.']);
                    }
                }
                
                if ($request->routeIs('super-admin.hero-section.update') || $request->routeIs('super-admin.hero-section.store')) {
                    // If updating, redirect to edit page
                    $heroSectionId = $request->route('heroSection');
                    if ($heroSectionId) {
                        return redirect()->route('super-admin.hero-section.edit', $heroSectionId)
                            ->withErrors(['error' => 'Sessão expirada. Por favor, recarregue a página e tente novamente.'])
                            ->withInput($request->except('password', '_token'));
                    }
                    // If creating, redirect to create page with preserved input
                    return redirect()->route('super-admin.hero-section.create')
                        ->withErrors(['error' => 'Sessão expirada. Por favor, recarregue a página e tente novamente.'])
                        ->withInput($request->except('password', '_token'));
                }
                
                // For other hero-section routes, redirect back
                return redirect()->back()
                    ->withErrors(['error' => 'Sessão expirada. Por favor, recarregue a página e tente novamente.'])
                    ->withInput($request->except('password', '_token'));
            }
            
            // For other routes, show a simple error message
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Sessão expirada. Por favor, recarregue a página.'], 419);
            }
            
            // Regenerate session for other routes too
            if ($request->hasSession()) {
                $request->session()->regenerate();
            }
            
            return redirect()->back()
                ->withErrors(['error' => 'Sessão expirada. Por favor, recarregue a página e tente novamente.'])
                ->withInput($request->except('password', '_token'));
        });
    })->create();