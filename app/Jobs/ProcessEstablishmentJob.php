<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\EstablishmentApiService;
use App\Models\Establishment;
use Illuminate\Support\Facades\Log;

class ProcessEstablishmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutes
    public $tries = 3;

    protected $establishmentId;
    protected $action;

    /**
     * Create a new job instance.
     */
    public function __construct($establishmentId, $action = 'sync')
    {
        $this->establishmentId = $establishmentId;
        $this->action = $action;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $establishment = Establishment::find($this->establishmentId);
            
            if (!$establishment) {
                Log::warning("Establishment not found: {$this->establishmentId}");
                return;
            }
            
            $apiService = new EstablishmentApiService();
            
            switch ($this->action) {
                case 'sync':
                    $this->syncEstablishment($establishment, $apiService);
                    break;
                case 'refresh':
                    $this->refreshEstablishment($establishment, $apiService);
                    break;
                case 'merge':
                    $this->mergeEstablishment($establishment, $apiService);
                    break;
                default:
                    Log::warning("Unknown action: {$this->action}");
            }
            
        } catch (\Exception $e) {
            Log::error("ProcessEstablishmentJob failed: " . $e->getMessage(), [
                'establishment_id' => $this->establishmentId,
                'action' => $this->action,
                'error' => $e->getMessage()
            ]);
            
            throw $e; // Re-throw to trigger retry mechanism
        }
    }
    
    /**
     * Sync establishment with external API
     */
    private function syncEstablishment(Establishment $establishment, EstablishmentApiService $apiService)
    {
        if ($establishment->is_external) {
            // Refresh external establishment data
            $apiService->refreshEstablishmentData($establishment);
        } else {
            // Search for external data to merge
            $externalData = $apiService->searchAndMergeEstablishment(
                $establishment->name,
                $establishment->address
            );
            
            if ($externalData) {
                $this->mergeExternalData($establishment, $externalData);
            }
        }
    }
    
    /**
     * Refresh establishment data from external API
     */
    private function refreshEstablishment(Establishment $establishment, EstablishmentApiService $apiService)
    {
        if ($establishment->is_external && $establishment->external_id) {
            $apiService->refreshEstablishmentData($establishment);
        }
    }
    
    /**
     * Merge external data with user-created establishment
     */
    private function mergeEstablishment(Establishment $establishment, EstablishmentApiService $apiService)
    {
        if (!$establishment->is_external) {
            $externalData = $apiService->searchAndMergeEstablishment(
                $establishment->name,
                $establishment->address
            );
            
            if ($externalData) {
                $this->mergeExternalData($establishment, $externalData);
            }
        }
    }
    
    /**
     * Merge external data into establishment
     */
    private function mergeExternalData(Establishment $establishment, array $externalData)
    {
        $mergeData = [
            'external_id' => $externalData['external_id'],
            'external_source' => $externalData['external_source'],
            'external_data' => $externalData['external_data'],
            'place_id' => $externalData['place_id'],
            'is_merged' => true,
            'last_synced_at' => now(),
        ];
        
        // Only update fields that are empty or null
        $fieldsToUpdate = [
            'rating' => 'rating',
            'user_ratings_total' => 'user_ratings_total',
            'price_level' => 'price_level',
            'types' => 'types',
            'business_status' => 'business_status',
            'photos' => 'photos',
            'formatted_address' => 'formatted_address',
            'formatted_phone_number' => 'formatted_phone_number',
            'international_phone_number' => 'international_phone_number',
            'opening_hours_external' => 'opening_hours_external',
            'reviews_external' => 'reviews_external',
        ];
        
        foreach ($fieldsToUpdate as $field => $externalField) {
            if (empty($establishment->$field) && !empty($externalData[$externalField])) {
                $mergeData[$field] = $externalData[$externalField];
            }
        }
        
        $establishment->update($mergeData);
        
        Log::info("Merged external data for establishment: {$establishment->id}", [
            'establishment_id' => $establishment->id,
            'external_id' => $externalData['external_id'],
            'merged_fields' => array_keys($mergeData)
        ]);
    }
    
    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("ProcessEstablishmentJob permanently failed", [
            'establishment_id' => $this->establishmentId,
            'action' => $this->action,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}