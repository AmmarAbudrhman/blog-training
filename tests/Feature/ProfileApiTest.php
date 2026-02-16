<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_show_profile()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/auth/profile');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                ]
            ]);
    }

    public function test_can_update_profile()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'name' => 'Updated Name',
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ];

        $response = $this->putJson('/api/auth/profile', $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Profile updated'
            ]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
    }
}
