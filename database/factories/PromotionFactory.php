<?php

namespace Database\Factories;

use App\Models\Establishment;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Promotion>
 */
class PromotionFactory extends Factory
{
    protected $model = Promotion::class;

    public function definition(): array
    {
        return [
            'establishment_id' => Establishment::factory(),
            'title' => $this->faker->sentence(3),
            'slug' => $this->faker->unique()->slug(),
            'promotion_type' => 'percentage',
            'discount_value' => $this->faker->numberBetween(5, 40),
            'status' => 'active',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
        ];
    }
}












