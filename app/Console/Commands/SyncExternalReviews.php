<?php

namespace App\Console\Commands;

use App\Models\Establishment;
use App\Services\ReviewService;
use Illuminate\Console\Command;

class SyncExternalReviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reviews:sync-external 
                            {--establishment= : Sync reviews for specific establishment ID}
                            {--limit=50 : Maximum number of establishments to process}
                            {--force : Force sync even if recently synced}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync external reviews from Google Places API for establishments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting external reviews sync...');
        
        $establishmentId = $this->option('establishment');
        $limit = (int) $this->option('limit');
        $force = $this->option('force');
        
        if ($establishmentId) {
            $this->syncSpecificEstablishment($establishmentId);
        } else {
            $this->syncMultipleEstablishments($limit, $force);
        }
        
        $this->info('External reviews sync completed!');
    }
    
    /**
     * Sync reviews for a specific establishment
     */
    private function syncSpecificEstablishment($establishmentId)
    {
        $establishment = Establishment::find($establishmentId);
        
        if (!$establishment) {
            $this->error("Establishment with ID {$establishmentId} not found.");
            return;
        }
        
        if (!$establishment->external_id) {
            $this->error("Establishment '{$establishment->name}' has no external ID.");
            return;
        }
        
        $this->info("Syncing reviews for: {$establishment->name}");
        
        $reviewService = app(ReviewService::class);
        $result = $reviewService->syncExternalReviews($establishment);
        
        if ($result !== false) {
            $this->info("✓ Synced {$result['synced']} new reviews, updated {$result['updated']} existing reviews");
        } else {
            $this->error("✗ Failed to sync reviews for {$establishment->name}");
        }
    }
    
    /**
     * Sync reviews for multiple establishments
     */
    private function syncMultipleEstablishments($limit, $force)
    {
        $query = Establishment::whereNotNull('external_id')
            ->where('external_source', 'google_places')
            ->where('permanently_closed', false);
        
        if (!$force) {
            // Only sync establishments that haven't been synced in the last 24 hours
            $query->where(function($q) {
                $q->whereNull('last_synced_at')
                  ->orWhere('last_synced_at', '<', now()->subHours(24));
            });
        }
        
        $establishments = $query->limit($limit)->get();
        
        if ($establishments->isEmpty()) {
            $this->info('No establishments need review sync at this time.');
            return;
        }
        
        $this->info("Found {$establishments->count()} establishments to sync reviews for.");
        
        $reviewService = app(ReviewService::class);
        $results = $reviewService->bulkSyncExternalReviews($establishments);
        
        $this->info("Sync Results:");
        $this->info("✓ Successful: {$results['successful']}");
        $this->info("✗ Failed: {$results['failed']}");
        $this->info("📊 Total: {$results['total']}");
        
        if ($results['failed'] > 0) {
            $this->warn("Failed establishments:");
            foreach ($results['details'] as $detail) {
                if (isset($detail['error'])) {
                    $this->line("  - {$detail['name']} (ID: {$detail['establishment_id']})");
                }
            }
        }
        
        // Show successful syncs with details
        if ($results['successful'] > 0) {
            $this->info("Successful syncs:");
            foreach ($results['details'] as $detail) {
                if (isset($detail['result'])) {
                    $result = $detail['result'];
                    $this->line("  - {$detail['name']}: {$result['synced']} new, {$result['updated']} updated");
                }
            }
        }
    }
}
