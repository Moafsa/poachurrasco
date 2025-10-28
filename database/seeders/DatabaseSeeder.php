<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test users (only if they don't exist)
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        // Seed establishments (includes user creation)
        $this->call(EstablishmentSeeder::class);
        
        // Seed BBQ guides
        $this->call(BbqGuideSeeder::class);
        
        // Seed external reviews (only if establishments with external_id exist)
        $this->call(ExternalReviewSeeder::class);
    }
}
