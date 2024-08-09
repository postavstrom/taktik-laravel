<?php

namespace tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_list_posts()
    {
        $response = $this->get('/api/posts');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_post()
    {
        $response = $this->post('/api/posts', [
            'title' => 'Test Post',
            'text' => 'This is a test post',
            'slug' => 'test-post',
            'user_id' => $this->user->id,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Post created successfully',
            ]);
    }

    /** @test */
    public function it_can_show_post()
    {
        $post = Post::factory()->create();

        $response = $this->get('/api/posts/' . $post->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $post->id,
            ]);
    }

    /** @test */
    public function it_can_update_post()
    {
        $post = Post::factory()->create();

        $response = $this->put('/api/posts/' . $post->id, [
            'title' => 'Updated Title',
            'text' => 'Updated text',
            'slug' => 'updated-title',
            'user_id' => $this->user->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Post updated successfully',
            ]);
    }

    /** @test */
    public function it_can_delete_post()
    {
        $post = Post::factory()->create();

        $response = $this->delete('/api/posts/' . $post->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Post deleted successfully',
            ]);
    }
}
