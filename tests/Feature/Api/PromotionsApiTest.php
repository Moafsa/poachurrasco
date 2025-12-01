<?php

namespace Tests\Feature\Api;

use App\Models\Establishment;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PromotionsApiTest extends TestCase
{
    use RefreshDatabase;

    private function createEstablishment(User $user): Establishment
    {
        return Establishment::factory()->create([
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_list_promotions(): void
    {
        $user = User::factory()->create();
        $establishment = $this->createEstablishment($user);
        Promotion::factory()->count(2)->create([
            'establishment_id' => $establishment->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->getJson('/api/dashboard/promotions');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [['id', 'title', 'promotion_type']],
                'meta' => ['current_page', 'per_page', 'total', 'last_page'],
            ]);
    }

    public function test_user_can_create_promotion(): void
    {
        $user = User::factory()->create();
        $establishment = $this->createEstablishment($user);
        $product = Product::factory()->create(['establishment_id' => $establishment->id]);

        $payload = [
            'establishment_id' => $establishment->id,
            'title' => 'Weekend Discount',
            'promotion_type' => 'percentage',
            'discount_value' => 15,
            'status' => 'active',
            'starts_at' => Carbon::now()->subHour()->toDateTimeString(),
            'ends_at' => Carbon::now()->addDay()->toDateTimeString(),
            'applicable_products' => [$product->id],
        ];

        $response = $this->actingAs($user)->postJson('/api/dashboard/promotions', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'Weekend Discount');

        $this->assertDatabaseHas('promotions', [
            'title' => 'Weekend Discount',
            'establishment_id' => $establishment->id,
        ]);
    }

    public function test_user_can_update_promotion(): void
    {
        $user = User::factory()->create();
        $establishment = $this->createEstablishment($user);
        $promotion = Promotion::factory()->create([
            'establishment_id' => $establishment->id,
            'discount_value' => 10,
        ]);

        $response = $this->actingAs($user)->putJson("/api/dashboard/promotions/{$promotion->id}", [
            'discount_value' => 25,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.discount_value', 25.0);

        $this->assertDatabaseHas('promotions', [
            'id' => $promotion->id,
            'discount_value' => 25,
        ]);
    }

    public function test_user_can_delete_promotion(): void
    {
        $user = User::factory()->create();
        $establishment = $this->createEstablishment($user);
        $promotion = Promotion::factory()->create([
            'establishment_id' => $establishment->id,
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/dashboard/promotions/{$promotion->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('promotions', ['id' => $promotion->id]);
    }
}












