<?php

namespace Tests\Feature;

use App\Models\Establishment;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_with_highlights(): void
    {
        $establishment = Establishment::factory()->create([
            'is_featured' => true,
            'is_verified' => true,
            'status' => 'active',
        ]);

        Product::factory()->create([
            'establishment_id' => $establishment->id,
            'is_featured' => true,
            'is_active' => true,
        ]);

        Promotion::factory()->create([
            'establishment_id' => $establishment->id,
            'status' => 'active',
            'is_featured' => true,
        ]);

        Service::factory()->create([
            'establishment_id' => $establishment->id,
            'is_featured' => true,
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertViewIs('public.home')
            ->assertSee('Featured barbecue spots');
    }

    public function test_public_products_page_displays_catalog(): void
    {
        $establishment = Establishment::factory()->create(['status' => 'active']);
        Product::factory()->create([
            'name' => 'Costela Gaúcha',
            'establishment_id' => $establishment->id,
            'is_active' => true,
        ]);

        $response = $this->get('/produtos');

        $response->assertOk()
            ->assertViewIs('public.products')
            ->assertSee('Costela Gaúcha');
    }

    public function test_public_promotions_page_displays_active_promotions(): void
    {
        $establishment = Establishment::factory()->create(['status' => 'active']);
        Promotion::factory()->create([
            'title' => 'Festival da Costela',
            'establishment_id' => $establishment->id,
            'status' => 'active',
        ]);

        $response = $this->get('/promocoes');

        $response->assertOk()
            ->assertViewIs('public.promotions')
            ->assertSee('Festival da Costela');
    }

    public function test_public_services_page_displays_active_services(): void
    {
        $establishment = Establishment::factory()->create(['status' => 'active']);
        Service::factory()->create([
            'name' => 'Churrasco Premium',
            'establishment_id' => $establishment->id,
            'is_active' => true,
        ]);

        $response = $this->get('/servicos');

        $response->assertOk()
            ->assertViewIs('public.services')
            ->assertSee('Churrasco Premium');
    }
}












