<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_a_post()
    {
        $user = \App\Models\User::factory()->create();
        $post = \App\Models\Post::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/likes', [
            'post_id' => $post->id,
        ]);

        $response->assertStatus(201); // Assuming 201 for resource created

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function test_user_cannot_like_same_post_twice()
    {
        $user = \App\Models\User::factory()->create();
        $post = \App\Models\Post::factory()->create();

        $user->likedPosts()->attach($post->id);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/likes', [
            'post_id' => $post->id,
        ]);

        $response->assertStatus(409); // Conflict
    }

    public function test_user_can_unlike_a_post()
    {
        $user = \App\Models\User::factory()->create();
        $post = \App\Models\Post::factory()->create();

        $user->likedPosts()->attach($post->id);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/likes/{$post->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }
}
