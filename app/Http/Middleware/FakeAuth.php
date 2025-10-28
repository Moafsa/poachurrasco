<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FakeAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is already authenticated
        if (!auth()->check()) {
            // Create a fake user for testing
            $user = new \App\Models\User();
            $user->id = 1;
            $user->name = 'Usuário Teste';
            $user->email = 'teste@exemplo.com';
            $user->role = 'user';
            $user->is_active = true;
            
            // Set the user as authenticated
            auth()->login($user);
        }
        
        return $next($request);
    }
}