<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/auth/signup', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'user',
                'authorization' => [
                    'token',
                    'type',
                ]
            ]);
        
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'user',
                'authorization' => [
                    'token',
                    'type',
                ]
            ]);
    }

    public function test_user_login_fails_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Unauthorized',
            ]);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'User is logged out']);
    }

    public function test_user_can_refresh_token()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        // Sleep for a second just to ensure tokens are slightly different if granularity allows,
        // but JWT usually has iat.
        // Also note that refreshing often invalidates the old one depending on blacklist settings.
        
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/auth/refresh');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'user',
                'authorization' => [
                    'token',
                    'type',
                ]
            ]);
        
        $this->assertNotEquals($token, $response->json('authorization.token'));
    }
}
