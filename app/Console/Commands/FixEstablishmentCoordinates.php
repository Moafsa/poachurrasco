<?php

namespace App\Console\Commands;

use App\Models\Establishment;
use App\Services\EstablishmentApiService;
use Illuminate\Console\Command;

class FixEstablishmentCoordinates extends Command
{
    protected $signature = 'establishments:fix-coordinates';
    protected $description = 'Fix missing coordinates for external establishments using external_data or API';

    public function handle()
    {
        $establishments = Establishment::where('is_external', true)
            ->where(function ($q) {
                $q->whereNull('latitude')
                  ->orWhereNull('longitude');
            })
            ->get();

        $this->info("Found {$establishments->count()} establishments without coordinates");

        $fixed = 0;
        $failed = 0;
        $apiService = app(EstablishmentApiService::class);

        foreach ($establishments as $establishment) {
            try {
                // First try to get from external_data
                $externalData = is_string($establishment->external_data) 
                    ? json_decode($establishment->external_data, true) 
                    : $establishment->external_data;

                $lat = null;
                $lng = null;

                if (isset($externalData['geometry']['location'])) {
                    $lat = $externalData['geometry']['location']['lat'] ?? null;
                    $lng = $externalData['geometry']['location']['lng'] ?? null;
                }

                // If not found, try to get from API
                if ((!$lat || !$lng) && ($establishment->place_id || $establishment->external_id)) {
                    $placeId = $establishment->place_id ?? $establishment->external_id;
                    $this->line("Fetching details for: {$establishment->name} (place_id: {$placeId})");
                    
                    $placeDetails = $apiService->getPlaceDetails($placeId);
                    
                    if ($placeDetails && isset($placeDetails['geometry']['location'])) {
                        $lat = $placeDetails['geometry']['location']['lat'] ?? null;
                        $lng = $placeDetails['geometry']['location']['lng'] ?? null;
                    }
                }

                if ($lat && $lng) {
                    $establishment->update([
                        'latitude' => $lat,
                        'longitude' => $lng,
                    ]);
                    $fixed++;
                    $this->line("✓ Fixed: {$establishment->name} ({$lat}, {$lng})");
                } else {
                    $failed++;
                    $this->warn("✗ Could not get coordinates for: {$establishment->name}");
                }
            } catch (\Exception $e) {
                $failed++;
                $this->error("✗ Error processing {$establishment->name}: " . $e->getMessage());
            }
        }

        $this->info("\nFixed: {$fixed}");
        $this->info("Failed: {$failed}");

        return Command::SUCCESS;
    }
}
