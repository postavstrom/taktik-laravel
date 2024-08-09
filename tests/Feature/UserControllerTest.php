<?php

namespace tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_users()
    {
        $response = $this->get('/api/users');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_create_user()
    {
        $response = $this->post('/api/users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',

        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'User created successfully',
            ]);
    }

    /** @test */
    public function it_can_show_user()
    {
        $user = User::factory()->create();

        $response = $this->get('/api/users/' . $user->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
            ]);
    }

    /** @test */
    public function it_can_update_user()
    {
        $user = User::factory()->create();

        $response = $this->put('/api/users/' . $user->id, [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User updated successfully',
            ]);
    }

    /** @test */
    public function it_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->delete('/api/users/' . $user->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User deleted successfully',
            ]);
    }
}
