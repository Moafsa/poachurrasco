<?php

namespace Tests\Feature\Api;

use App\Models\Establishment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicesApiTest extends TestCase
{
    use RefreshDatabase;

    private function createEstablishment(User $user): Establishment
    {
        return Establishment::factory()->create(['user_id' => $user->id]);
    }

    public function test_user_can_list_services(): void
    {
        $user = User::factory()->create();
        $establishment = $this->createEstablishment($user);
        Service::factory()->count(2)->create([
            'establishment_id' => $establishment->id,
        ]);

        $response = $this->actingAs($user)->getJson('/api/dashboard/services');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [['id', 'name', 'category']],
            ]);
    }

    public function test_user_can_create_service(): void
    {
        $user = User::factory()->create();
        $establishment = $this->createEstablishment($user);

        $payload = [
            'establishment_id' => $establishment->id,
            'name' => 'Full Barbecue Experience',
            'category' => Service::CATEGORIES[0],
            'price' => 950.50,
            'duration_minutes' => 240,
            'capacity' => 30,
            'is_active' => true,
        ];

        $response = $this->actingAs($user)->postJson('/api/dashboard/services', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Full Barbecue Experience');

        $this->assertDatabaseHas('services', [
            'name' => 'Full Barbecue Experience',
            'establishment_id' => $establishment->id,
        ]);
    }

    public function test_user_can_update_service(): void
    {
        $user = User::factory()->create();
        $establishment = $this->createEstablishment($user);
        $service = Service::factory()->create([
            'establishment_id' => $establishment->id,
            'price' => 500,
        ]);

        $response = $this->actingAs($user)->putJson("/api/dashboard/services/{$service->id}", [
            'price' => 575.75,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.price', 575.75);

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'price' => 575.75,
        ]);
    }

    public function test_user_can_delete_service(): void
    {
        $user = User::factory()->create();
        $establishment = $this->createEstablishment($user);
        $service = Service::factory()->create([
            'establishment_id' => $establishment->id,
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/dashboard/services/{$service->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }
}












