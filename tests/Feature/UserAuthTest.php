<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        // Register the user without authentication needed
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => ['name', 'email'],
            'token'
        ]);
    }

    public function test_user_can_login()
    {
        // First, create a user
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);

        // Now try logging in
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token'
        ]);
    }

    public function test_user_can_access_authenticated_routes()
    {
        // First, create a user and authenticate using Sanctum
        $user = User::factory()->create();

        // Use actingAs to authenticate the user
        $response = $this->actingAs($user)->getJson('/api/user');
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $user->id,
            'name' => $user->name,
        ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        // Attempt to login with invalid credentials
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthorized']);
    }
}
