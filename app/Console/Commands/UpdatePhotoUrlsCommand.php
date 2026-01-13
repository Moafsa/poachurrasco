<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Establishment;
use Illuminate\Support\Facades\DB;

class UpdatePhotoUrlsCommand extends Command
{
    protected $signature = 'establishments:update-photo-urls {--limit=50 : Maximum number of establishments to process}';
    protected $description = 'Update photo URLs for establishments using the current API key';

    public function handle()
    {
        $limit = (int) $this->option('limit');
        $newApiKey = config('services.google.places_api_key');
        
        if (!$newApiKey) {
            $this->error('Google Places API key not configured!');
            return Command::FAILURE;
        }

        $this->info("Updating photo URLs with API key: " . substr($newApiKey, 0, 20) . "...");
        $this->info("Processing up to {$limit} establishments...");

        $establishments = Establishment::where('status', 'active')
            ->whereNotNull('photo_urls')
            ->whereNotNull('photos')
            ->limit($limit)
            ->get();

        $updated = 0;
        $errors = 0;

        foreach ($establishments as $establishment) {
            try {
                if (empty($establishment->photos) || !is_array($establishment->photos)) {
                    continue;
                }

                $photoUrls = [];
                foreach ($establishment->photos as $photo) {
                    if (isset($photo['photo_reference'])) {
                        $photoUrl = 'https://maps.googleapis.com/maps/api/place/photo?' . http_build_query([
                            'maxwidth' => 400,
                            'photoreference' => $photo['photo_reference'],
                            'key' => $newApiKey
                        ]);
                        $photoUrls[] = $photoUrl;
                    }
                }

                if (!empty($photoUrls)) {
                    $establishment->photo_urls = $photoUrls;
                    $establishment->save();
                    $updated++;
                    $this->line("✓ Updated: {$establishment->name} ({$establishment->id}) - " . count($photoUrls) . " photos");
                }
            } catch (\Exception $e) {
                $errors++;
                $this->warn("✗ Error updating {$establishment->name} ({$establishment->id}): " . $e->getMessage());
            }
        }

        $this->info("\n✅ Update completed!");
        $this->info("Updated: {$updated}");
        $this->info("Errors: {$errors}");

        return Command::SUCCESS;
    }
}
