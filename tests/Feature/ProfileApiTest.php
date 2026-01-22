<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_show_profile()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->getJson('/api/auth/profile', ['Authorization' => 'Bearer ' . $token]);

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
        $token = JWTAuth::fromUser($user);

        $data = [
            'name' => 'Updated Name',
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ];

        $response = $this->putJson('/api/auth/profile', $data, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Profile updated'
            ]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
    }
}
