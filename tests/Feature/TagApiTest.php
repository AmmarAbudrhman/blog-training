<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_all_tags()
    {
        Tag::factory()->count(3)->create();

        $response = $this->getJson('/api/tags');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_tag()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/tags', [
            'name' => 'Laravel',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Laravel']);

        $this->assertDatabaseHas('tags', ['name' => 'Laravel']);
    }

    public function test_cannot_create_duplicate_tag()
    {
        $user = User::factory()->create();
        Tag::factory()->create(['name' => 'Laravel']);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/tags', [
            'name' => 'Laravel',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('name');
    }

    public function test_can_update_tag()
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/tags/{$tag->id}", [
            'name' => 'New Name',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'New Name']);
            
        $this->assertDatabaseHas('tags', ['id' => $tag->id, 'name' => 'New Name']);
    }

    public function test_can_delete_tag()
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/tags/{$tag->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }
}
