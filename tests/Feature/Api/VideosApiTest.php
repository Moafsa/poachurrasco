<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideosApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_videos(): void
    {
        $user = User::factory()->create();
        Video::factory()->count(2)->create();

        $response = $this->actingAs($user)->getJson('/api/dashboard/videos');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [['id', 'title', 'video_url']],
            ]);
    }

    public function test_user_can_create_video(): void
    {
        $user = User::factory()->create();

        $payload = [
            'title' => 'How to Grill the Perfect Steak',
            'category' => 'tutorial',
            'video_url' => 'https://www.youtube.com/watch?v=abcdefghijk',
            'provider' => 'youtube',
            'is_published' => true,
        ];

        $response = $this->actingAs($user)->postJson('/api/dashboard/videos', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'How to Grill the Perfect Steak');

        $this->assertDatabaseHas('videos', [
            'title' => 'How to Grill the Perfect Steak',
            'provider' => 'youtube',
        ]);
    }

    public function test_user_can_update_video(): void
    {
        $user = User::factory()->create();
        $video = Video::factory()->create([
            'title' => 'Behind the scenes',
        ]);

        $response = $this->actingAs($user)->putJson("/api/dashboard/videos/{$video->id}", [
            'title' => 'Behind the scenes - Extended',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.title', 'Behind the scenes - Extended');

        $this->assertDatabaseHas('videos', [
            'id' => $video->id,
            'title' => 'Behind the scenes - Extended',
        ]);
    }

    public function test_user_can_delete_video(): void
    {
        $user = User::factory()->create();
        $video = Video::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/dashboard/videos/{$video->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('videos', ['id' => $video->id]);
    }
}












