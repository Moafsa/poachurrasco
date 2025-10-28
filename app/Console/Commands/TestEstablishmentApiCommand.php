<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EstablishmentApiService;
use App\Models\Establishment;

class TestEstablishmentApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'establishments:test-api 
                            {--query=churrascaria : Search query to test}
                            {--radius=50000 : Search radius in meters}
                            {--limit=5 : Maximum number of results to show}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the establishment API integration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Establishment API Integration...');
        
        $query = $this->option('query');
        $radius = (int) $this->option('radius');
        $limit = (int) $this->option('limit');
        
        $this->info("Searching for: {$query}");
        $this->info("Radius: {$radius}m");
        $this->info("Limit: {$limit} results");
        
        try {
            $apiService = new EstablishmentApiService();
            
            // Test API search
            $this->info("\n1. Testing API search...");
            $establishments = $apiService->searchEstablishmentsInPortoAlegre($query, $radius);
            
            if (empty($establishments)) {
                $this->warn("No establishments found for query: {$query}");
                return Command::SUCCESS;
            }
            
            $this->info("Found " . count($establishments) . " establishments");
            
            // Show first few results
            $this->info("\n2. Sample results:");
            $sampleResults = array_slice($establishments, 0, $limit);
            
            foreach ($sampleResults as $index => $establishment) {
                $this->info("--- Establishment " . ($index + 1) . " ---");
                $this->info("Name: " . ($establishment['name'] ?? 'N/A'));
                $this->info("Address: " . ($establishment['formatted_address'] ?? 'N/A'));
                $this->info("Rating: " . ($establishment['rating'] ?? 'N/A'));
                $this->info("External ID: " . ($establishment['external_id'] ?? 'N/A'));
                $this->info("Category: " . ($establishment['category'] ?? 'N/A'));
                $this->info("Latitude: " . ($establishment['latitude'] ?? 'N/A'));
                $this->info("Longitude: " . ($establishment['longitude'] ?? 'N/A'));
                $this->info("");
            }
            
            // Test database sync
            $this->info("3. Testing database sync...");
            $result = $apiService->syncEstablishmentsToDatabase($sampleResults);
            
            $this->info("Sync results:");
            $this->info("- Synced: {$result['synced']}");
            $this->info("- Updated: {$result['updated']}");
            $this->info("- Errors: {$result['errors']}");
            
            // Show database count
            $totalCount = Establishment::count();
            $externalCount = Establishment::external()->count();
            $userCreatedCount = Establishment::userCreated()->count();
            
            $this->info("\n4. Database statistics:");
            $this->info("- Total establishments: {$totalCount}");
            $this->info("- External establishments: {$externalCount}");
            $this->info("- User-created establishments: {$userCreatedCount}");
            
            // Test place details
            if (!empty($sampleResults) && isset($sampleResults[0]['external_id'])) {
                $this->info("\n5. Testing place details...");
                $placeId = $sampleResults[0]['external_id'];
                $details = $apiService->getPlaceDetails($placeId);
                
                if ($details) {
                    $this->info("Place details retrieved successfully");
                    $this->info("Place name: " . ($details['name'] ?? 'N/A'));
                    $this->info("Phone: " . ($details['formatted_phone_number'] ?? 'N/A'));
                    $this->info("Website: " . ($details['website'] ?? 'N/A'));
                } else {
                    $this->warn("Could not retrieve place details");
                }
            }
            
            $this->info("\n✅ API integration test completed successfully!");
            
        } catch (\Exception $e) {
            $this->error("❌ API integration test failed: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}