<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Establishment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Toggle favorite status for an establishment
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'establishment_id' => 'required|exists:establishments,id',
        ]);

        $userId = Auth::id();
        $establishmentId = $request->establishment_id;

        $favorite = Favorite::where('user_id', $userId)
            ->where('establishment_id', $establishmentId)
            ->first();

        if ($favorite) {
            // Remove from favorites
            $favorite->delete();
            $isFavorited = false;
            $message = 'Removido dos favoritos';
        } else {
            // Add to favorites
            Favorite::create([
                'user_id' => $userId,
                'establishment_id' => $establishmentId,
            ]);
            $isFavorited = true;
            $message = 'Adicionado aos favoritos';
        }

        return response()->json([
            'success' => true,
            'is_favorited' => $isFavorited,
            'message' => $message,
        ]);
    }

    /**
     * Get user's favorite establishments
     */
    public function index()
    {
        $favorites = Auth::user()->favoriteEstablishments()
            ->with(['reviews' => function($query) {
                $query->verified();
            }])
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    /**
     * Check if establishment is favorited by user
     */
    public function check(Request $request)
    {
        $request->validate([
            'establishment_id' => 'required|exists:establishments,id',
        ]);

        $isFavorited = Favorite::where('user_id', Auth::id())
            ->where('establishment_id', $request->establishment_id)
            ->exists();

        return response()->json([
            'is_favorited' => $isFavorited,
        ]);
    }
}