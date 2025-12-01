<?php

namespace Tests\Feature\Api;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipesApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_recipes(): void
    {
        $user = User::factory()->create();
        Recipe::factory()->count(2)->create();

        $response = $this->actingAs($user)->getJson('/api/dashboard/recipes');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [['id', 'title', 'category']],
            ]);
    }

    public function test_user_can_create_recipe(): void
    {
        $user = User::factory()->create();

        $payload = [
            'title' => 'Smoked Beef Ribs',
            'category' => Recipe::CATEGORIES[0],
            'difficulty' => Recipe::DIFFICULTIES[1],
            'servings' => 6,
            'ingredients' => "Beef ribs\nSalt\nPepper",
            'instructions' => "Season ribs\nSmoke for 6 hours\nRest and serve",
            'is_published' => true,
        ];

        $response = $this->actingAs($user)->postJson('/api/dashboard/recipes', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'Smoked Beef Ribs');

        $this->assertDatabaseHas('recipes', [
            'title' => 'Smoked Beef Ribs',
            'is_published' => true,
        ]);
    }

    public function test_user_can_update_recipe(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create([
            'title' => 'Classic Picanha',
        ]);

        $response = $this->actingAs($user)->putJson("/api/dashboard/recipes/{$recipe->id}", [
            'title' => 'Classic Picanha Deluxe',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.title', 'Classic Picanha Deluxe');

        $this->assertDatabaseHas('recipes', [
            'id' => $recipe->id,
            'title' => 'Classic Picanha Deluxe',
        ]);
    }

    public function test_user_can_delete_recipe(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/dashboard/recipes/{$recipe->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    }
}












