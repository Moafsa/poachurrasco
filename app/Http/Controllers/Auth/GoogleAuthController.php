<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            if (!$googleUser || !$googleUser->email) {
                \Log::error('Google OAuth: Invalid user data received');
                return redirect('/login')->withErrors(['email' => 'Não foi possível obter informações do Google. Tente novamente.']);
            }
            
            // Check if user exists by Google ID first, then by email
            $user = User::where('google_id', $googleUser->id)->first();
            
            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();
            }

            if ($user) {
                // Check if user is active
                if (!$user->is_active) {
                    \Log::warning('Google OAuth: Attempt to login with inactive user', ['email' => $googleUser->email]);
                    return redirect('/login')->withErrors(['email' => 'Sua conta está desativada. Entre em contato com o administrador.']);
                }
                
                // Update Google ID if not set
                if (!$user->google_id) {
                    $user->google_id = $googleUser->id;
                }
                
                // Update avatar if not set or if Google has a newer one
                if ($googleUser->avatar && (!$user->avatar || $googleUser->avatar !== $user->avatar)) {
                    $user->avatar = $googleUser->avatar;
                }
                
                // Ensure user is verified since Google email is verified
                if (!$user->email_verified_at) {
                    $user->email_verified_at = now();
                }
                
                $user->save();
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name ?? 'User',
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar ?? null,
                    'password' => Hash::make(Str::random(32)), // Random secure password for OAuth users
                    'email_verified_at' => now(),
                    'role' => 'user',
                    'is_active' => true,
                ]);
            }

            // Login user
            Auth::login($user, true);

            // Merge session cart to user cart if exists
            try {
                $sessionId = request()->session()->getId();
                \App\Models\Cart::mergeSessionCartToUser($sessionId, $user->id);
            } catch (\Exception $e) {
                \Log::warning('Failed to merge session cart on Google login', ['error' => $e->getMessage()]);
            }

            \Log::info('Google OAuth: User logged in successfully', ['email' => $user->email]);

            return redirect()->intended(route('dashboard'));

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            \Log::error('Google OAuth: InvalidStateException', ['error' => $e->getMessage()]);
            return redirect('/login')->withErrors(['email' => 'Sessão expirada. Tente fazer login novamente.']);
        } catch (\Exception $e) {
            \Log::error('Google OAuth: Error during callback', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorMessage = config('app.debug') 
                ? 'Erro ao fazer login com Google: ' . $e->getMessage()
                : 'Erro ao fazer login com Google. Tente novamente.';
                
            return redirect('/login')->withErrors(['email' => $errorMessage]);
        }
    }

    /**
     * Logout user
     */
    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/')->with('status', 'You have been signed out.');
    }
}