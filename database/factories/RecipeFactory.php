<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recipe>
 */
class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'slug' => $this->faker->unique()->slug(),
            'category' => $this->faker->randomElement(Recipe::CATEGORIES),
            'difficulty' => $this->faker->randomElement(Recipe::DIFFICULTIES),
            'servings' => $this->faker->numberBetween(2, 8),
            'ingredients' => $this->faker->words(5),
            'instructions' => $this->faker->sentences(3),
            'is_published' => true,
        ];
    }
}












