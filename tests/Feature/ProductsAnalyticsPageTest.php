<?php

namespace Tests\Feature;

use App\Models\Establishment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsAnalyticsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_products_analytics(): void
    {
        $user = User::factory()->create();
        $establishment = Establishment::factory()->create(['user_id' => $user->id]);

        Product::factory()->create([
            'establishment_id' => $establishment->id,
            'name' => 'Smoked Brisket',
            'price' => 120,
            'stock_quantity' => 4,
            'low_stock_threshold' => 6,
            'track_stock' => true,
            'purchase_count' => 8,
            'view_count' => 145,
            'is_featured' => true,
        ]);

        $response = $this->actingAs($user)->get(route('products.analytics'));

        $response->assertOk()
            ->assertViewIs('dashboard.products.analytics')
            ->assertSee('Inventory Analytics')
            ->assertSee('Smoked Brisket')
            ->assertSee('Inventory overview');
    }
}












