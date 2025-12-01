<?php

namespace App\Services;

use App\Models\Establishment;
use App\Models\ExternalReview;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ReviewService
{
    private const GOOGLE_PLACES_API_URL = 'https://maps.googleapis.com/maps/api/place';
    
    private $googleApiKey;
    
    public function __construct()
    {
        $this->googleApiKey = config('services.google.places_api_key');
    }
    
    /**
     * Fetch and sync external reviews for an establishment
     */
    public function syncExternalReviews(Establishment $establishment)
    {
        if (!$establishment->external_id || !$establishment->external_source) {
            Log::warning("Establishment {$establishment->id} has no external_id or external_source");
            return false;
        }
        
        $cacheKey = "external_reviews_{$establishment->external_id}";
        
        return Cache::remember($cacheKey, 3600, function() use ($establishment) {
            try {
                $placeDetails = $this->getPlaceDetailsWithReviews($establishment->external_id);
                
                if (!$placeDetails || !isset($placeDetails['reviews'])) {
                    Log::info("No reviews found for establishment {$establishment->id}");
                    return false;
                }
                
                $syncedCount = 0;
                $updatedCount = 0;
                
                foreach ($placeDetails['reviews'] as $reviewData) {
                    $result = $this->processExternalReview($establishment, $reviewData);
                    if ($result === 'created') {
                        $syncedCount++;
                    } elseif ($result === 'updated') {
                        $updatedCount++;
                    }
                }
                
                // Update establishment's external review count
                $this->updateEstablishmentExternalReviewStats($establishment);
                
                Log::info("Synced external reviews for establishment {$establishment->id}: {$syncedCount} new, {$updatedCount} updated");
                
                return [
                    'synced' => $syncedCount,
                    'updated' => $updatedCount,
                    'total' => count($placeDetails['reviews'])
                ];
                
            } catch (\Exception $e) {
                Log::error("Error syncing external reviews for establishment {$establishment->id}: " . $e->getMessage());
                return false;
            }
        });
    }
    
    /**
     * Get place details including reviews from Google Places API
     */
    private function getPlaceDetailsWithReviews($placeId)
    {
        try {
            $response = Http::get(self::GOOGLE_PLACES_API_URL . '/details/json', [
                'place_id' => $placeId,
                'fields' => 'reviews,rating,user_ratings_total',
                'key' => $this->googleApiKey,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['result'] ?? null;
            }
            
            Log::error("Failed to fetch place details for {$placeId}: " . $response->body());
            return null;
            
        } catch (\Exception $e) {
            Log::error("Error fetching place details for {$placeId}: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Process and store/update an external review
     */
    private function processExternalReview(Establishment $establishment, array $reviewData)
    {
        try {
            // Create a unique external ID for the review
            $externalId = $this->generateExternalReviewId($establishment->external_id, $reviewData);
            
            $existingReview = ExternalReview::where('external_id', $externalId)->first();
            
            $reviewData = [
                'establishment_id' => $establishment->id,
                'external_id' => $externalId,
                'external_source' => 'google_places',
                'author_name' => $reviewData['author_name'] ?? 'Anonymous',
                'author_url' => $reviewData['author_url'] ?? null,
                'profile_photo_url' => $reviewData['profile_photo_url'] ?? null,
                'rating' => $reviewData['rating'] ?? 0,
                'text' => $reviewData['text'] ?? null,
                'time' => isset($reviewData['time']) ? Carbon::createFromTimestamp($reviewData['time']) : now(),
                'language' => $reviewData['language'] ?? null,
                'original_data' => $reviewData,
                'is_verified' => true,
            ];
            
            if ($existingReview) {
                $existingReview->update($reviewData);
                return 'updated';
            } else {
                ExternalReview::create($reviewData);
                return 'created';
            }
            
        } catch (\Exception $e) {
            Log::error("Error processing external review: " . $e->getMessage(), [
                'establishment_id' => $establishment->id,
                'review_data' => $reviewData
            ]);
            return false;
        }
    }
    
    /**
     * Generate a unique external review ID
     */
    private function generateExternalReviewId($placeId, $reviewData)
    {
        // Use author_name + time + first few chars of text as unique identifier
        $uniqueString = ($reviewData['author_name'] ?? '') . 
                       ($reviewData['time'] ?? '') . 
                       substr($reviewData['text'] ?? '', 0, 50);
        
        return $placeId . '_' . md5($uniqueString);
    }
    
    /**
     * Update establishment's external review statistics
     */
    private function updateEstablishmentExternalReviewStats(Establishment $establishment)
    {
        $externalReviews = ExternalReview::where('establishment_id', $establishment->id)
            ->where('external_source', 'google_places')
            ->get();
        
        if ($externalReviews->count() > 0) {
            $averageRating = $externalReviews->avg('rating');
            $totalReviews = $externalReviews->count();
            
            // Update external review stats (keep separate from internal reviews)
            $establishment->update([
                'external_rating' => round($averageRating, 2),
                'external_review_count' => $totalReviews,
            ]);
        }
    }
    
    /**
     * Get combined reviews (internal + external) for an establishment
     */
    public function getCombinedReviews(Establishment $establishment, $limit = 10)
    {
        $internalReviews = $establishment->reviews()
            ->with('user')
            ->verified()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($review) {
                return [
                    'id' => $review->id,
                    'type' => 'internal',
                    'author_name' => $review->user->name ?? 'Anonymous',
                    'author_avatar' => null,
                    'rating' => $review->rating,
                    'text' => $review->comment,
                    'time' => $review->created_at,
                    'time_ago' => $review->created_at->diffForHumans(),
                    'is_verified' => $review->is_verified,
                    'images' => $review->images,
                ];
            });
        
        $externalReviews = $establishment->externalReviews()
            ->verified()
            ->orderBy('time', 'desc')
            ->get()
            ->map(function($review) {
                return [
                    'id' => $review->id,
                    'type' => 'external',
                    'author_name' => $review->author_name,
                    'author_avatar' => $review->profile_photo_url,
                    'rating' => $review->rating,
                    'text' => $review->text,
                    'time' => $review->time,
                    'time_ago' => $review->time_ago,
                    'is_verified' => $review->is_verified,
                    'images' => null,
                ];
            });
        
        // Combine and sort by time
        $allReviews = $internalReviews->concat($externalReviews)
            ->sortByDesc('time')
            ->take($limit);
        
        return $allReviews->values();
    }
    
    /**
     * Get overall rating combining internal and external reviews
     */
    public function getOverallRating(Establishment $establishment)
    {
        $internalReviews = $establishment->reviews()->verified()->get();
        $externalReviews = $establishment->externalReviews()->verified()->get();
        
        $allRatings = $internalReviews->pluck('rating')
            ->concat($externalReviews->pluck('rating'));
        
        if ($allRatings->count() > 0) {
            return [
                'rating' => round($allRatings->avg(), 2),
                'total_reviews' => $allRatings->count(),
                'internal_reviews' => $internalReviews->count(),
                'external_reviews' => $externalReviews->count(),
            ];
        }
        
        return [
            'rating' => 0,
            'total_reviews' => 0,
            'internal_reviews' => 0,
            'external_reviews' => 0,
        ];
    }
    
    /**
     * Bulk sync external reviews for multiple establishments
     */
    public function bulkSyncExternalReviews($establishments, $useQueue = true)
    {
        $results = [
            'total' => count($establishments),
            'successful' => 0,
            'failed' => 0,
            'details' => []
        ];
        
        foreach ($establishments as $establishment) {
            if ($useQueue) {
                // Dispatch job for async processing
                \App\Jobs\SyncExternalReviewsJob::dispatch($establishment);
                $results['successful']++;
                $results['details'][] = [
                    'establishment_id' => $establishment->id,
                    'name' => $establishment->name,
                    'queued' => true
                ];
            } else {
                // Process synchronously
                $result = $this->syncExternalReviews($establishment);
                
                if ($result !== false) {
                    $results['successful']++;
                    $results['details'][] = [
                        'establishment_id' => $establishment->id,
                        'name' => $establishment->name,
                        'result' => $result
                    ];
                } else {
                    $results['failed']++;
                    $results['details'][] = [
                        'establishment_id' => $establishment->id,
                        'name' => $establishment->name,
                        'error' => 'Failed to sync reviews'
                    ];
                }
            }
        }
        
        return $results;
    }
}



