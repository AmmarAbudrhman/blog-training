<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_posts()
    {
        Post::factory()->count(3)->create();

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'items' => [
                        '*' => [
                            'id',
                            'title',
                            'body',
                        ]
                    ],
                    'pagination'
                ]
            ]);
    }

    public function test_can_show_post()
    {
        $post = Post::factory()->create();

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'body',
                ]
            ]);
    }

    public function test_can_create_post()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $data = [
            'title' => 'Test Post',
            'body' => 'Some body',
            'image' => UploadedFile::fake()->image('post.jpg'),
        ];

        $response = $this->postJson('/api/posts', $data, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                ]
            ]);
        $this->assertDatabaseHas('posts', ['title' => 'Test Post']);
    }

    public function test_can_update_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $token = JWTAuth::fromUser($user);

        $data = [
            'title' => 'Updated Post Title',
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $data, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'Updated Post Title',
                ]
            ]);

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Updated Post Title']);
    }

    public function test_can_delete_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $token = JWTAuth::fromUser($user);

        $response = $this->deleteJson("/api/posts/{$post->id}", [], ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
