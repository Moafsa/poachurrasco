<?php

namespace App\Jobs;

use App\Models\Establishment;
use App\Services\ReviewService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncExternalReviewsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $establishment;
    public $tries = 3;
    public $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(Establishment $establishment)
    {
        $this->establishment = $establishment;
    }

    /**
     * Execute the job.
     */
    public function handle(ReviewService $reviewService): void
    {
        try {
            Log::info("Syncing external reviews for establishment: {$this->establishment->id}");

            $result = $reviewService->syncExternalReviews($this->establishment);

            if ($result) {
                Log::info("Successfully synced reviews for establishment {$this->establishment->id}", [
                    'synced' => $result['synced'] ?? 0,
                    'updated' => $result['updated'] ?? 0,
                ]);
            } else {
                Log::warning("Failed to sync reviews for establishment {$this->establishment->id}");
            }
        } catch (\Exception $e) {
            Log::error("Error syncing external reviews for establishment {$this->establishment->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Job failed for establishment {$this->establishment->id}: " . $exception->getMessage());
    }
}


