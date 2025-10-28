<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EstablishmentApiService;
use App\Models\Establishment;
use Illuminate\Support\Facades\Log;

class SyncEstablishmentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'establishments:sync 
                            {--refresh : Refresh existing establishments data}
                            {--search-terms=* : Search terms to use (default: churrascaria,açougue,supermercado,restaurante)}
                            {--radius=50000 : Search radius in meters}
                            {--limit=100 : Maximum number of establishments to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize establishments from external APIs (Google Places)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting establishments synchronization...');
        
        $apiService = new EstablishmentApiService();
        $refresh = $this->option('refresh');
        $searchTerms = $this->option('search-terms') ?: ['churrascaria', 'açougue', 'supermercado', 'restaurante', 'bar', 'lanchonete'];
        $radius = (int) $this->option('radius');
        $limit = (int) $this->option('limit');
        
        $totalSynced = 0;
        $totalUpdated = 0;
        $totalErrors = 0;
        
        if ($refresh) {
            $this->info('Refreshing existing establishments data...');
            $this->refreshExistingEstablishments($apiService);
        }
        
        $this->info('Searching for new establishments...');
        
        foreach ($searchTerms as $term) {
            $this->info("Searching for: {$term}");
            
            try {
                $establishments = $apiService->searchEstablishmentsInPortoAlegre($term, $radius);
                
                if (empty($establishments)) {
                    $this->warn("No establishments found for term: {$term}");
                    continue;
                }
                
                // Limit the number of establishments to process
                if (count($establishments) > $limit) {
                    $establishments = array_slice($establishments, 0, $limit);
                    $this->warn("Limited to {$limit} establishments for term: {$term}");
                }
                
                $result = $apiService->syncEstablishmentsToDatabase($establishments);
                
                $totalSynced += $result['synced'];
                $totalUpdated += $result['updated'];
                $totalErrors += $result['errors'];
                
                $this->info("Term '{$term}': {$result['synced']} synced, {$result['updated']} updated, {$result['errors']} errors");
                
            } catch (\Exception $e) {
                $this->error("Error processing term '{$term}': " . $e->getMessage());
                Log::error("SyncEstablishmentsCommand error for term '{$term}': " . $e->getMessage());
                $totalErrors++;
            }
        }
        
        // Clean up old external establishments that are permanently closed
        $this->cleanupClosedEstablishments();
        
        $this->info('Synchronization completed!');
        $this->info("Total synced: {$totalSynced}");
        $this->info("Total updated: {$totalUpdated}");
        $this->info("Total errors: {$totalErrors}");
        
        return Command::SUCCESS;
    }
    
    /**
     * Refresh existing establishments data
     */
    private function refreshExistingEstablishments(EstablishmentApiService $apiService)
    {
        $establishments = $apiService->getEstablishmentsNeedingSync(24);
        
        if ($establishments->isEmpty()) {
            $this->info('No establishments need refreshing.');
            return;
        }
        
        $this->info("Refreshing {$establishments->count()} establishments...");
        
        $result = $apiService->bulkRefreshEstablishments($establishments);
        
        $this->info("Refreshed: {$result['refreshed']}, Errors: {$result['errors']}");
    }
    
    /**
     * Clean up permanently closed establishments
     */
    private function cleanupClosedEstablishments()
    {
        $closedCount = Establishment::external()
            ->where('permanently_closed', true)
            ->where('last_synced_at', '<', now()->subDays(30))
            ->count();
            
        if ($closedCount > 0) {
            Establishment::external()
                ->where('permanently_closed', true)
                ->where('last_synced_at', '<', now()->subDays(30))
                ->update(['status' => 'inactive']);
                
            $this->info("Marked {$closedCount} permanently closed establishments as inactive.");
        }
    }
}