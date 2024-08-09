<?php

namespace tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $post;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->post = Post::factory()->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function can_create_comment()
    {
        $response = $this->post('/api/comments', [
            'text' => 'This is a test comment',
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Comment created successfully',
            ]);
    }

    /** @test */
    public function can_view_comment()
    {
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);

        $response = $this->get('/api/comments/' . $comment->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $comment->id,
            ]);
    }

    /** @test */
    public function can_update_comment()
    {
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);

        $response = $this->put('/api/comments/' . $comment->id, [
            'text' => 'Updated comment text',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Comment updated successfully',
            ]);
    }

    /** @test */
    public function can_delete_comment()
    {
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);

        $response = $this->delete('/api/comments/' . $comment->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Comment deleted successfully',
            ]);
    }
}
