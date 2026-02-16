<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_comments_for_a_post()
    {
        $post = Post::factory()->create();
        $comments = Comment::factory()->count(3)->create(['post_id' => $post->id]);

        $response = $this->getJson("/api/comments/post/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/comments', [
            'post_id' => $post->id,
            'content' => 'This is a test comment',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['content' => 'This is a test comment']);

        $this->assertDatabaseHas('comments', [
            'content' => 'This is a test comment',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function test_can_update_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/comments/{$comment->id}", [
            'content' => 'Updated content',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['content' => 'Updated content']);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Updated content',
        ]);
    }

    public function test_cannot_update_others_comment()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($otherUser, 'sanctum')->putJson("/api/comments/{$comment->id}", [
            'content' => 'Hacked content',
        ]);

        $response->assertStatus(403);
    }

    public function test_can_delete_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
