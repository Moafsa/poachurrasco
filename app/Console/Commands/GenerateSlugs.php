<?php

namespace App\Console\Commands;

use App\Models\Establishment;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateSlugs extends Command
{
    protected $signature = 'establishments:generate-slugs';
    protected $description = 'Generate slugs for establishments that do not have one';

    public function handle()
    {
        $establishments = Establishment::whereNull('slug')
            ->orWhere('slug', '')
            ->get();

        $this->info("Found {$establishments->count()} establishments without slugs");

        foreach ($establishments as $establishment) {
            $originalSlug = Str::slug($establishment->name);
            $slug = $originalSlug;
            $count = 1;

            // Ensure uniqueness
            while (Establishment::where('slug', $slug)->where('id', '!=', $establishment->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            $establishment->slug = $slug;
            $establishment->save();

            $this->line("Generated slug '{$slug}' for establishment: {$establishment->name}");
        }

        $this->info('All slugs generated successfully!');
        return 0;
    }
}



