<?php

namespace Database\Factories;

use App\Models\Establishment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Establishment>
 */
class EstablishmentFactory extends Factory
{
    protected $model = Establishment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->company(),
            'address' => $this->faker->streetAddress(),
            'city' => 'Porto Alegre',
            'state' => 'RS',
            'zip_code' => '90000-000',
            'category' => 'churrascaria',
            'status' => 'active',
        ];
    }
}












