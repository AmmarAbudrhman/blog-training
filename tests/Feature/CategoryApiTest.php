<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Catgories;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_categories()
    {
        Catgories::factory()->count(3)->create();

        $response = $this->getJson('/api/catgories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'items' => [
                        '*' => [
                            'id',
                            'name',
                            'image',
                        ]
                    ],
                    'pagination'
                ]
            ]);
    }

    public function test_can_show_category()
    {
        $category = Catgories::factory()->create();

        $response = $this->getJson("/api/catgories/{$category->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'image',
                ]
            ]);
    }

    public function test_can_create_category()
    {
        Storage::fake('public');

        $data = [
            'name' => 'Test Category',
            'image' => UploadedFile::fake()->image('category.jpg'),
        ];

        $response = $this->postJson('/api/catgories', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                ]
            ]);
        
        $this->assertDatabaseHas('catgories', ['name' => 'Test Category']);
    }

    public function test_can_update_category()
    {
        $category = Catgories::factory()->create();

        $data = [
            'name' => 'Updated Category Name',
        ];

        $response = $this->putJson("/api/catgories/{$category->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'Updated Category Name',
                ]
            ]);
        
        $this->assertDatabaseHas('catgories', ['id' => $category->id, 'name' => 'Updated Category Name']);
    }

    public function test_can_delete_category()
    {
        $category = Catgories::factory()->create();

        $response = $this->deleteJson("/api/catgories/{$category->id}");

        $response->assertStatus(200);
        
        $this->assertDatabaseMissing('catgories', ['id' => $category->id]);
    }
}
