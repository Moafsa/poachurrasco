<?php

namespace Database\Factories;

use App\Models\Establishment;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'establishment_id' => Establishment::factory(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'category' => $this->faker->randomElement(Product::CATEGORIES),
            'price' => $this->faker->randomFloat(2, 10, 200),
            'is_active' => true,
            'stock_quantity' => $this->faker->numberBetween(0, 50),
            'track_stock' => true,
        ];
    }
}












