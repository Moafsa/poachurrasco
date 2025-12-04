<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info('EnsureUserIsAdmin middleware', [
            'authenticated' => auth()->check(),
            'user_id' => auth()->id(),
            'user_role' => auth()->check() ? auth()->user()->role : 'none',
            'is_admin' => auth()->check() ? auth()->user()->isAdmin() : false,
        ]);
        
        if (!auth()->check()) {
            \Log::warning('User not authenticated');
            abort(403, 'Unauthorized. Admin access required.');
        }
        
        if (!auth()->user()->isAdmin()) {
            \Log::warning('User is not admin', [
                'user_id' => auth()->id(),
                'user_role' => auth()->user()->role,
            ]);
            abort(403, 'Unauthorized. Admin access required.');
        }

        return $next($request);
    }
}



