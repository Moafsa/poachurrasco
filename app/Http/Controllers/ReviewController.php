<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Establishment;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Get combined reviews for an establishment (internal + external)
     */
    public function getCombinedReviews($id)
    {
        // Accept ID (numeric) or slug
        $establishment = Establishment::where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();
        
        $page = request()->get('page', 1);
        $perPage = 10;
        
        $reviewService = app(ReviewService::class);
        $allReviews = $reviewService->getCombinedReviews($establishment, 100); // Get more to paginate
        
        // Paginate manually
        $total = count($allReviews);
        $offset = ($page - 1) * $perPage;
        $reviews = array_slice($allReviews, $offset, $perPage);
        
        $overallRating = $reviewService->getOverallRating($establishment);
        
        return response()->json([
            'reviews' => $reviews,
            'overall_rating' => $overallRating,
            'current_page' => (int) $page,
            'total' => $total,
            'per_page' => $perPage,
            'has_more' => ($offset + $perPage) < $total
        ]);
    }
    
    /**
     * Sync external reviews for an establishment
     */
    public function syncExternalReviews($id)
    {
        // Accept ID (numeric) or slug
        $establishment = Establishment::where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();
        
        $reviewService = app(ReviewService::class);
        $result = $reviewService->syncExternalReviews($establishment);
        
        if ($result !== false) {
            return response()->json([
                'success' => true,
                'message' => 'External reviews synced successfully',
                'data' => $result
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to sync external reviews'
        ], 500);
    }
    
    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        $request->validate([
            'establishment_id' => 'required|exists:establishments,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if user already reviewed this establishment
        $existingReview = Review::where('user_id', Auth::id())
            ->where('establishment_id', $request->establishment_id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Você já avaliou este estabelecimento.');
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'establishment_id' => $request->establishment_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'images' => $request->images ? array_map(function($image) {
                return $image->store('reviews', 'public');
            }, $request->images) : null,
            'is_verified' => false,
        ]);

        // Update establishment rating
        $this->updateEstablishmentRating($request->establishment_id);

        return back()->with('success', 'Avaliação enviada com sucesso!');
    }

    /**
     * Update an existing review
     */
    public function update(Request $request, Review $review)
    {
        // Check if user owns this review
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Você não pode editar esta avaliação.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'images' => $request->images ? array_map(function($image) {
                return $image->store('reviews', 'public');
            }, $request->images) : $review->images,
        ]);

        // Update establishment rating
        $this->updateEstablishmentRating($review->establishment_id);

        return back()->with('success', 'Avaliação atualizada com sucesso!');
    }

    /**
     * Delete a review
     */
    public function destroy(Review $review)
    {
        // Check if user owns this review or is admin
        if ($review->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return back()->with('error', 'Você não pode excluir esta avaliação.');
        }

        $establishmentId = $review->establishment_id;
        $review->delete();

        // Update establishment rating
        $this->updateEstablishmentRating($establishmentId);

        return back()->with('success', 'Avaliação excluída com sucesso!');
    }

    /**
     * Update establishment rating based on reviews (internal + external)
     */
    private function updateEstablishmentRating($establishmentId)
    {
        $establishment = Establishment::find($establishmentId);
        
        if ($establishment) {
            $reviewService = app(ReviewService::class);
            $overallRating = $reviewService->getOverallRating($establishment);
            
            $establishment->update([
                'rating' => $overallRating['rating'],
                'review_count' => $overallRating['total_reviews'],
                'external_rating' => $overallRating['external_reviews'] > 0 ? 
                    \App\Models\ExternalReview::where('establishment_id', $establishmentId)->avg('rating') : null,
                'external_review_count' => $overallRating['external_reviews'],
            ]);
        }
    }
}