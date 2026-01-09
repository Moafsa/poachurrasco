<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Establishment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;
    protected Establishment $establishment;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        Queue::fake();

        $this->user = User::factory()->create();
        $this->establishment = Establishment::factory()->create(['user_id' => $this->user->id]);
        $this->product = Product::factory()->create([
            'establishment_id' => $this->establishment->id,
            'is_active' => true,
            'price' => 50.00,
        ]);
    }

    /** @test */
    public function authenticated_user_can_create_order()
    {
        $cart = Cart::getOrCreateForUser($this->user->id);
        $cart->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $response = $this->actingAs($this->user)->postJson('/api/orders', [
            'establishment_id' => $this->establishment->id,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 2,
                ],
            ],
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'customer_phone' => '51999999999',
            'type' => 'pickup',
            'payment_method' => 'pix',
        ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'establishment_id' => $this->establishment->id,
            'status' => 'pending',
        ]);

        // Assert jobs were dispatched
        Queue::assertPushed(\App\Jobs\ProcessOrderJob::class);
        Queue::assertPushed(\App\Jobs\SendOrderConfirmationJob::class);
        Queue::assertPushed(\App\Jobs\UpdateInventoryJob::class);
        Queue::assertPushed(\App\Jobs\NotifyEstablishmentJob::class);
    }

    /** @test */
    public function cannot_create_order_without_items()
    {
        $response = $this->actingAs($this->user)->postJson('/api/orders', [
            'establishment_id' => $this->establishment->id,
            'items' => [],
            'customer_name' => 'John Doe',
            'customer_phone' => '51999999999',
            'type' => 'pickup',
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function cannot_create_order_with_inactive_product()
    {
        $inactiveProduct = Product::factory()->create([
            'establishment_id' => $this->establishment->id,
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->user)->postJson('/api/orders', [
            'establishment_id' => $this->establishment->id,
            'items' => [
                [
                    'product_id' => $inactiveProduct->id,
                    'quantity' => 1,
                ],
            ],
            'customer_name' => 'John Doe',
            'customer_phone' => '51999999999',
            'type' => 'pickup',
        ]);

        $response->assertStatus(400);
    }

    /** @test */
    public function delivery_order_requires_delivery_address()
    {
        $response = $this->actingAs($this->user)->postJson('/api/orders', [
            'establishment_id' => $this->establishment->id,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 1,
                ],
            ],
            'customer_name' => 'John Doe',
            'customer_phone' => '51999999999',
            'type' => 'delivery',
            'payment_method' => 'pix',
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function order_calculates_totals_correctly()
    {
        $cart = Cart::getOrCreateForUser($this->user->id);
        $cart->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $response = $this->actingAs($this->user)->postJson('/api/orders', [
            'establishment_id' => $this->establishment->id,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 2,
                ],
            ],
            'customer_name' => 'John Doe',
            'customer_phone' => '51999999999',
            'type' => 'pickup',
            'payment_method' => 'pix',
        ]);

        $response->assertStatus(201);

        $order = Order::where('user_id', $this->user->id)->first();
        $this->assertEquals(100.00, $order->subtotal); // 2 * 50.00
        $this->assertEquals(0.00, $order->delivery_fee);
        $this->assertEquals(100.00, $order->total);
    }
}


















