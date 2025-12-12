<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default: show external establishments
        SystemSetting::set(
            'show_external_establishments',
            true,
            'boolean',
            'Control whether external establishments from Google Places are shown on public pages (Home and Map)'
        );
    }
}





















