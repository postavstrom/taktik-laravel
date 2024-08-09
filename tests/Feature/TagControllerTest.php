<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_tag()
    {
        $tagData = ['name' => 'Test Tag'];

        $response = $this->postJson('/api/tags', $tagData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Tag created successfully',
            ])
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('tags', $tagData);
    }

    /** @test */
    public function it_can_view_tag()
    {
        $tag = Tag::factory()->create();

        $response = $this->getJson("/api/tags/{$tag->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $tag->id,
                'name' => $tag->name,
            ])
            ->assertJsonStructure([
                'id',
                'name',
                'created_at',
                'updated_at',
            ]);
    }

    /** @test */
    public function it_can_update_tag()
    {
        $tag = Tag::factory()->create();
        $updateData = ['name' => 'Updated Tag Name'];

        $response = $this->putJson("/api/tags/{$tag->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Tag updated successfully',
            ])
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('tags', $updateData);
    }

    /** @test */
    public function it_can_delete_tag()
    {
        $tag = Tag::factory()->create();

        $response = $this->deleteJson("/api/tags/{$tag->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Tag deleted successfully',
            ]);

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }
}
