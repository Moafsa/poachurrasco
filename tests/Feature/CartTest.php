<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Establishment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;
    protected Establishment $establishment;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->establishment = Establishment::factory()->create(['user_id' => $this->user->id]);
        $this->product = Product::factory()->create([
            'establishment_id' => $this->establishment->id,
            'is_active' => true,
            'price' => 50.00,
        ]);
    }

    /** @test */
    public function guest_can_add_product_to_cart()
    {
        $response = $this->postJson('/api/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('carts', [
            'session_id' => session()->getId(),
        ]);
    }

    /** @test */
    public function authenticated_user_can_add_product_to_cart()
    {
        $response = $this->actingAs($this->user)->postJson('/api/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function can_get_cart_items()
    {
        $cart = Cart::getOrCreateForUser($this->user->id);
        $cart->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $response = $this->actingAs($this->user)->getJson('/api/cart');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'items' => [
                    '*' => ['product_id', 'product_name', 'quantity', 'price', 'total']
                ],
                'subtotal',
                'count',
            ]);
    }

    /** @test */
    public function can_update_cart_item_quantity()
    {
        $cart = Cart::getOrCreateForUser($this->user->id);
        $cart->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $response = $this->actingAs($this->user)->putJson('/api/cart/update', [
            'product_id' => $this->product->id,
            'quantity' => 5,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $this->product->id,
            'quantity' => 5,
        ]);
    }

    /** @test */
    public function can_remove_item_from_cart()
    {
        $cart = Cart::getOrCreateForUser($this->user->id);
        $cart->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $response = $this->actingAs($this->user)->deleteJson("/api/cart/remove/{$this->product->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('cart_items', [
            'product_id' => $this->product->id,
        ]);
    }

    /** @test */
    public function can_clear_cart()
    {
        $cart = Cart::getOrCreateForUser($this->user->id);
        $cart->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $response = $this->actingAs($this->user)->deleteJson('/api/cart/clear');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertEquals(0, $cart->fresh()->items()->count());
    }

    /** @test */
    public function cannot_add_inactive_product_to_cart()
    {
        $inactiveProduct = Product::factory()->create([
            'establishment_id' => $this->establishment->id,
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->user)->postJson('/api/cart/add', [
            'product_id' => $inactiveProduct->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(400)
            ->assertJson(['success' => false]);
    }

    /** @test */
    public function cannot_add_more_than_available_stock()
    {
        $limitedProduct = Product::factory()->create([
            'establishment_id' => $this->establishment->id,
            'is_active' => true,
            'track_stock' => true,
            'stock_quantity' => 5,
            'allow_backorder' => false,
        ]);

        $response = $this->actingAs($this->user)->postJson('/api/cart/add', [
            'product_id' => $limitedProduct->id,
            'quantity' => 10,
        ]);

        $response->assertStatus(400)
            ->assertJson(['success' => false]);
    }

    /** @test */
    public function can_calculate_cart_totals()
    {
        $cart = Cart::getOrCreateForUser($this->user->id);
        $cart->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $response = $this->actingAs($this->user)->postJson('/api/cart/calculate-totals', [
            'type' => 'pickup',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'subtotal',
                'delivery_fee',
                'discount',
                'total',
            ]);
    }
}


















