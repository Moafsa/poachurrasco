<?php

namespace Tests\Feature\Api;

use App\Models\Establishment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsApiTest extends TestCase
{
    use RefreshDatabase;

    private function createEstablishment(User $user): Establishment
    {
        return Establishment::create([
            'user_id' => $user->id,
            'name' => 'Smoke House',
            'address' => '123 BBQ Street',
            'city' => 'Porto Alegre',
            'state' => 'RS',
            'zip_code' => '90000-000',
            'category' => 'churrascaria',
            'status' => 'active',
        ]);
    }

    public function test_user_can_list_products(): void
    {
        $user = User::factory()->create();
        $establishment = $this->createEstablishment($user);

        Product::factory()->count(2)->create([
            'establishment_id' => $establishment->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->getJson('/api/dashboard/products');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [['id', 'name', 'category']],
                'meta' => ['current_page', 'per_page', 'total', 'last_page'],
            ]);
    }

    public function test_user_can_create_product(): void
    {
        $user = User::factory()->create();
        $establishment = $this->createEstablishment($user);

        $payload = [
            'establishment_id' => $establishment->id,
            'name' => 'Picanha Premium',
            'category' => Product::CATEGORIES[0],
            'price' => 129.90,
            'stock_quantity' => 5,
            'track_stock' => true,
            'is_active' => true,
        ];

        $response = $this->actingAs($user)->postJson('/api/dashboard/products', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Picanha Premium');

        $this->assertDatabaseHas('products', [
            'name' => 'Picanha Premium',
            'establishment_id' => $establishment->id,
        ]);
    }

    public function test_user_can_update_product(): void
    {
        $user = User::factory()->create();
        $establishment = $this->createEstablishment($user);
        $product = Product::factory()->create([
            'establishment_id' => $establishment->id,
            'price' => 100,
        ]);

        $response = $this->actingAs($user)->putJson("/api/dashboard/products/{$product->id}", [
            'price' => 110,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.price', 110.0);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'price' => 110,
        ]);
    }

    public function test_user_can_delete_product(): void
    {
        $user = User::factory()->create();
        $establishment = $this->createEstablishment($user);
        $product = Product::factory()->create([
            'establishment_id' => $establishment->id,
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/dashboard/products/{$product->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}












