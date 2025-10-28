<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\EstablishmentController;
use Illuminate\Http\Request;

class TestMapDataCommand extends Command
{
    protected $signature = 'test:map-data';
    protected $description = 'Test the map data endpoint';

    public function handle()
    {
        $this->info('Testing map data endpoint...');
        
        $controller = new EstablishmentController();
        $request = new Request();
        $request->merge([
            'include_external' => true
            // Remove proximity filter to test all establishments
        ]);
        
        try {
            $response = $controller->mapData($request);
            $data = $response->getData();
            
            $this->info('Map data retrieved successfully!');
            $this->info('Total establishments: ' . count($data->establishments));
            $this->info('External establishments: ' . collect($data->establishments)->where('is_external', true)->count());
            
            $this->info('Sample establishments:');
            foreach (collect($data->establishments)->take(3) as $establishment) {
                $this->line("- {$establishment->name} ({$establishment->category}) - Rating: {$establishment->rating}");
            }
            
        } catch (\Exception $e) {
            $this->error('Error testing map data: ' . $e->getMessage());
        }
    }
}