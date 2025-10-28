<?php

namespace Database\Seeders;

use App\Models\Establishment;
use App\Models\ExternalReview;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ExternalReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only seed if we have establishments with external_id
        $establishments = Establishment::whereNotNull('external_id')
            ->where('external_source', 'google_places')
            ->take(5)
            ->get();

        if ($establishments->isEmpty()) {
            $this->command->info('No establishments with external_id found. Skipping external reviews seeding.');
            return;
        }

        $this->command->info('Seeding external reviews for ' . $establishments->count() . ' establishments...');

        foreach ($establishments as $establishment) {
            $this->seedExternalReviewsForEstablishment($establishment);
        }

        $this->command->info('External reviews seeded successfully!');
    }

    /**
     * Seed external reviews for a specific establishment
     */
    private function seedExternalReviewsForEstablishment(Establishment $establishment)
    {
        $sampleReviews = [
            [
                'author_name' => 'Carlos Silva',
                'rating' => 5,
                'text' => 'Excelente churrascaria! A carne estava perfeita e o atendimento foi impecável. Recomendo para quem quer uma experiência autêntica do churrasco gaúcho.',
                'time' => Carbon::now()->subDays(2),
            ],
            [
                'author_name' => 'Ana Maria Santos',
                'rating' => 4,
                'text' => 'Muito bom! Ambiente agradável e comida saborosa. O rodízio tem boa variedade e qualidade.',
                'time' => Carbon::now()->subDays(5),
            ],
            [
                'author_name' => 'João Pedro',
                'rating' => 5,
                'text' => 'Uma das melhores churrascarias de Porto Alegre. Carne de primeira qualidade e serviço excelente.',
                'time' => Carbon::now()->subDays(7),
            ],
            [
                'author_name' => 'Maria Fernanda',
                'rating' => 4,
                'text' => 'Boa experiência gastronômica. Preço justo pela qualidade oferecida.',
                'time' => Carbon::now()->subDays(10),
            ],
            [
                'author_name' => 'Roberto Lima',
                'rating' => 3,
                'text' => 'Bom, mas poderia melhorar no atendimento. A comida está boa.',
                'time' => Carbon::now()->subDays(15),
            ],
        ];

        foreach ($sampleReviews as $index => $reviewData) {
            $externalId = $establishment->external_id . '_review_' . ($index + 1);
            
            // Check if review already exists
            if (ExternalReview::where('external_id', $externalId)->exists()) {
                continue;
            }

            ExternalReview::create([
                'establishment_id' => $establishment->id,
                'external_id' => $externalId,
                'external_source' => 'google_places',
                'author_name' => $reviewData['author_name'],
                'author_url' => null,
                'profile_photo_url' => null,
                'rating' => $reviewData['rating'],
                'text' => $reviewData['text'],
                'time' => $reviewData['time'],
                'language' => 'pt-BR',
                'original_data' => $reviewData,
                'is_verified' => true,
            ]);
        }

        // Update establishment external review stats
        $this->updateEstablishmentStats($establishment);
    }

    /**
     * Update establishment external review statistics
     */
    private function updateEstablishmentStats(Establishment $establishment)
    {
        $externalReviews = ExternalReview::where('establishment_id', $establishment->id)
            ->where('external_source', 'google_places')
            ->get();

        if ($externalReviews->count() > 0) {
            $averageRating = $externalReviews->avg('rating');
            
            $establishment->update([
                'external_rating' => round($averageRating, 2),
                'external_review_count' => $externalReviews->count(),
            ]);
        }
    }
}
