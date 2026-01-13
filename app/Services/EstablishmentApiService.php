<?php

namespace App\Services;

use App\Models\Establishment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class EstablishmentApiService
{
    private const GOOGLE_PLACES_API_URL = 'https://maps.googleapis.com/maps/api/place';
    private const FOURSQUARE_API_URL = 'https://api.foursquare.com/v3';
    
    private $googleApiKey;
    private $foursquareApiKey;
    
    public function __construct()
    {
        $this->googleApiKey = config('services.google.places_api_key');
        $this->foursquareApiKey = config('services.foursquare.api_key');
    }
    
    /**
     * Search for establishments in Porto Alegre using Google Places API
     */
    public function searchEstablishmentsInPortoAlegre($query = 'churrascaria', $radius = 50000)
    {
        $cacheKey = "establishments_poa_{$query}_{$radius}";
        
        return Cache::remember($cacheKey, 3600, function() use ($query, $radius) {
            $establishments = [];
            
            // Porto Alegre coordinates
            $location = '-30.0346,-51.2177';
            
            try {
                // Search using Google Places Text Search
                $response = Http::get(self::GOOGLE_PLACES_API_URL . '/textsearch/json', [
                    'query' => $query,
                    'location' => $location,
                    'radius' => $radius,
                    'key' => $this->googleApiKey,
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['results'])) {
                        foreach ($data['results'] as $place) {
                            $establishments[] = $this->processGooglePlaceData($place);
                        }
                    }
                }
                
                // Also search using Nearby Search for more comprehensive results
                $nearbyResponse = Http::get(self::GOOGLE_PLACES_API_URL . '/nearbysearch/json', [
                    'location' => $location,
                    'radius' => $radius,
                    'type' => 'restaurant',
                    'keyword' => $query,
                    'key' => $this->googleApiKey,
                ]);
                
                if ($nearbyResponse->successful()) {
                    $nearbyData = $nearbyResponse->json();
                    if (isset($nearbyData['results'])) {
                        foreach ($nearbyData['results'] as $place) {
                            $establishments[] = $this->processGooglePlaceData($place);
                        }
                    }
                }
                
            } catch (\Exception $e) {
                Log::error('Error searching establishments in Porto Alegre: ' . $e->getMessage());
            }
            
            return $establishments;
        });
    }
    
    /**
     * Process Google Places API data into our establishment format
     */
    private function processGooglePlaceData($placeData)
    {
        $formattedAddress = $placeData['formatted_address'] ?? '';
        $address = $this->extractAddressFromFormatted($formattedAddress);
        $zipCode = $this->extractZipCodeFromFormatted($formattedAddress);
        $photoUrls = $this->convertPhotoReferencesToUrls($placeData['photos'] ?? []);
        
        return [
            'user_id' => null, // External establishments don't have a user_id
            'external_id' => $placeData['place_id'] ?? null,
            'external_source' => 'google_places',
            'external_data' => $placeData,
            'place_id' => $placeData['place_id'] ?? null,
            'name' => $placeData['name'] ?? 'Unknown',
            'address' => $address,
            'formatted_address' => $formattedAddress,
            'vicinity' => $placeData['vicinity'] ?? null,
            'latitude' => $placeData['geometry']['location']['lat'] ?? null,
            'longitude' => $placeData['geometry']['location']['lng'] ?? null,
            'rating' => $placeData['rating'] ?? 0,
            'user_ratings_total' => $placeData['user_ratings_total'] ?? 0,
            'price_level' => $placeData['price_level'] ?? null,
            'types' => $placeData['types'] ?? [],
            'business_status' => $placeData['business_status'] ?? 'OPERATIONAL',
            'permanently_closed' => $placeData['permanently_closed'] ?? false,
            'photos' => $placeData['photos'] ?? [],
            'photo_urls' => $photoUrls,
            'is_external' => true,
            'is_merged' => false,
            'status' => 'active',
            'city' => 'Porto Alegre',
            'state' => 'RS',
            'zip_code' => $zipCode,
            'category' => $this->mapGoogleTypesToCategory($placeData['types'] ?? []),
            'last_synced_at' => now(),
        ];
    }
    
    /**
     * Extract address from formatted address string
     */
    private function extractAddressFromFormatted($formattedAddress)
    {
        if (empty($formattedAddress)) {
            return 'Endereço não disponível';
        }
        
        // Split by comma and take the first part (street address)
        $parts = explode(',', $formattedAddress);
        return trim($parts[0] ?? 'Endereço não disponível');
    }
    
    /**
     * Extract zip code from formatted address string
     */
    private function extractZipCodeFromFormatted($formattedAddress)
    {
        if (empty($formattedAddress)) {
            return '';
        }
        
        // Look for Brazilian zip code pattern (5 digits - 3 digits)
        if (preg_match('/(\d{5}-\d{3})/', $formattedAddress, $matches)) {
            return $matches[1];
        }
        
        // Look for 5 digits pattern
        if (preg_match('/(\d{5})/', $formattedAddress, $matches)) {
            return $matches[1];
        }
        
        return '';
    }
    
    /**
     * Convert Google Places photo references to actual URLs
     */
    private function convertPhotoReferencesToUrls($photos)
    {
        if (empty($photos) || !is_array($photos)) {
            return [];
        }
        
        $photoUrls = [];
        
        foreach ($photos as $photo) {
            if (isset($photo['photo_reference'])) {
                $photoUrl = self::GOOGLE_PLACES_API_URL . '/photo?' . http_build_query([
                    'maxwidth' => 400,
                    'photoreference' => $photo['photo_reference'],
                    'key' => $this->googleApiKey
                ]);
                $photoUrls[] = $photoUrl;
            }
        }
        
        return $photoUrls;
    }
    
    /**
     * Map Google Places types to our category system
     */
    private function mapGoogleTypesToCategory($types)
    {
        $typeMapping = [
            'meal_takeaway' => 'delivery',
            'restaurant' => 'restaurante',
            'food' => 'restaurante',
            'bar' => 'bar',
            'cafe' => 'lanchonete',
            'meal_delivery' => 'delivery',
            'supermarket' => 'supermercado',
            'grocery_or_supermarket' => 'supermercado',
            'butcher' => 'açougue',
            'store' => 'outros',
        ];
        
        foreach ($types as $type) {
            if (isset($typeMapping[$type])) {
                return $typeMapping[$type];
            }
        }
        
        return 'outros';
    }
    
    /**
     * Get detailed information for a specific place
     */
    public function getPlaceDetails($placeId)
    {
        $cacheKey = "place_details_{$placeId}";
        
        return Cache::remember($cacheKey, 86400, function() use ($placeId) {
            try {
                $response = Http::get(self::GOOGLE_PLACES_API_URL . '/details/json', [
                    'place_id' => $placeId,
                    'fields' => 'name,formatted_address,formatted_phone_number,international_phone_number,opening_hours,website,photos,reviews,rating,user_ratings_total,price_level,types,business_status,geometry',
                    'key' => $this->googleApiKey,
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    return $data['result'] ?? null;
                }
            } catch (\Exception $e) {
                Log::error("Error getting place details for {$placeId}: " . $e->getMessage());
            }
            
            return null;
        });
    }
    
    /**
     * Sync establishments from external API to database
     */
    public function syncEstablishmentsToDatabase($establishments)
    {
        $synced = 0;
        $updated = 0;
        $errors = 0;
        
        foreach ($establishments as $rawEstablishmentData) {
            try {
                // If we don't have geometry data, fetch place details
                if (!isset($rawEstablishmentData['geometry']['location']) && isset($rawEstablishmentData['place_id'])) {
                    $placeDetails = $this->getPlaceDetails($rawEstablishmentData['place_id']);
                    if ($placeDetails) {
                        // Merge place details with raw data
                        $rawEstablishmentData = array_merge($rawEstablishmentData, $placeDetails);
                    }
                }
                
                // Process the raw data
                $establishmentData = $this->processGooglePlaceData($rawEstablishmentData);
                
                $existing = Establishment::where('external_id', $establishmentData['external_id'])
                    ->where('external_source', $establishmentData['external_source'])
                    ->first();
                
                if ($existing) {
                    // Update existing establishment
                    $existing->update($establishmentData);
                    $updated++;
                } else {
                    // Create new establishment
                    Establishment::create($establishmentData);
                    $synced++;
                }
            } catch (\Exception $e) {
                Log::error('Error syncing establishment: ' . $e->getMessage(), [
                    'establishment' => $establishmentData ?? $rawEstablishmentData
                ]);
                $errors++;
            }
        }
        
        return [
            'synced' => $synced,
            'updated' => $updated,
            'errors' => $errors,
            'total' => count($establishments)
        ];
    }
    
    /**
     * Search for establishments by name and merge with external data
     */
    public function searchAndMergeEstablishment($name, $address = null)
    {
        try {
            // Search in Google Places
            $query = $name;
            if ($address) {
                $query .= " {$address} Porto Alegre";
            }
            
            $response = Http::get(self::GOOGLE_PLACES_API_URL . '/textsearch/json', [
                'query' => $query,
                'key' => $this->googleApiKey,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['results']) && !empty($data['results'])) {
                    $place = $data['results'][0]; // Get the first result
                    return $this->processGooglePlaceData($place);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error searching establishment: ' . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Update establishment with fresh data from external API
     */
    public function refreshEstablishmentData(Establishment $establishment)
    {
        if (!$establishment->external_id || !$establishment->external_source) {
            return false;
        }
        
        try {
            $details = $this->getPlaceDetails($establishment->external_id);
            
            if ($details) {
                // Convert photo references to URLs
                $photoUrls = $this->convertPhotoReferencesToUrls($details['photos'] ?? []);
                
                $updatedData = [
                    'rating' => $details['rating'] ?? $establishment->rating,
                    'user_ratings_total' => $details['user_ratings_total'] ?? $establishment->user_ratings_total,
                    'business_status' => $details['business_status'] ?? $establishment->business_status,
                    'permanently_closed' => $details['permanently_closed'] ?? $establishment->permanently_closed,
                    'formatted_phone_number' => $details['formatted_phone_number'] ?? $establishment->formatted_phone_number,
                    'international_phone_number' => $details['international_phone_number'] ?? $establishment->international_phone_number,
                    'website' => $details['website'] ?? $establishment->website,
                    'opening_hours_external' => $details['opening_hours'] ?? $establishment->opening_hours_external,
                    'photos' => $details['photos'] ?? $establishment->photos,
                    'photo_urls' => $photoUrls,
                    'reviews_external' => $details['reviews'] ?? $establishment->reviews_external,
                    'last_synced_at' => now(),
                ];
                
                $establishment->update($updatedData);
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Error refreshing establishment data: ' . $e->getMessage());
        }
        
        return false;
    }
    
    /**
     * Get establishments that need synchronization
     */
    public function getEstablishmentsNeedingSync($hours = 24)
    {
        return Establishment::external()
            ->needsSync($hours)
            ->where('permanently_closed', false)
            ->get();
    }
    
    /**
     * Sync external reviews for an establishment using ReviewService
     */
    public function syncExternalReviews(Establishment $establishment)
    {
        $reviewService = app(\App\Services\ReviewService::class);
        return $reviewService->syncExternalReviews($establishment);
    }
    
    /**
     * Bulk refresh establishments
     */
    public function bulkRefreshEstablishments($establishments)
    {
        $refreshed = 0;
        $errors = 0;
        
        foreach ($establishments as $establishment) {
            if ($this->refreshEstablishmentData($establishment)) {
                $refreshed++;
            } else {
                $errors++;
            }
        }
        
        return [
            'refreshed' => $refreshed,
            'errors' => $errors,
            'total' => count($establishments)
        ];
    }
}
