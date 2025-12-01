<?php

namespace Database\Factories;

use App\Models\Establishment;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'establishment_id' => Establishment::factory(),
            'name' => $this->faker->sentence(3),
            'slug' => $this->faker->unique()->slug(),
            'category' => $this->faker->randomElement(Service::CATEGORIES),
            'price' => $this->faker->randomFloat(2, 100, 500),
            'duration_minutes' => $this->faker->numberBetween(60, 240),
            'capacity' => $this->faker->numberBetween(10, 100),
            'is_active' => true,
        ];
    }
}












