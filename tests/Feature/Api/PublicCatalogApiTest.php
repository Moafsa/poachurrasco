<?php

namespace Tests\Feature\Api;

use App\Models\Establishment;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCatalogApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_list_public_products(): void
    {
        $establishment = Establishment::factory()->create(['status' => 'active']);
        Product::factory()->create([
            'establishment_id' => $establishment->id,
            'name' => 'Linguiça Artesanal',
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/catalog/products');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [['id', 'name', 'price']],
                'meta' => ['current_page', 'per_page', 'total', 'last_page'],
            ])
            ->assertJsonFragment(['name' => 'Linguiça Artesanal']);
    }

    public function test_guest_can_list_public_promotions(): void
    {
        $establishment = Establishment::factory()->create(['status' => 'active']);
        Promotion::factory()->create([
            'title' => 'Semana do Costelão',
            'establishment_id' => $establishment->id,
            'status' => 'active',
        ]);

        $response = $this->getJson('/api/catalog/promotions');

        $response->assertOk()
            ->assertJsonFragment(['title' => 'Semana do Costelão']);
    }

    public function test_guest_can_list_public_services(): void
    {
        $establishment = Establishment::factory()->create(['status' => 'active']);
        Service::factory()->create([
            'name' => 'Churrasco Backyard',
            'establishment_id' => $establishment->id,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/catalog/services');

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Churrasco Backyard']);
    }
}











