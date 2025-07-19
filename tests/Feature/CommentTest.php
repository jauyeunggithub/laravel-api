<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_comment_on_post()
    {
        // Create a user and post
        $user = User::factory()->create();
        $post = Post::factory()->create();

        // Authenticate the user using Sanctum
        Sanctum::actingAs($user);

        // Create a comment on the post by passing the correct 'post_id' in the request
        $response = $this->postJson("/api/posts/{$post->id}/comments", [
            'content' => 'This is a test comment.',
        ]);

        // Ensure the status and message are correct
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Comment posted and email sent',
        ]);

        // Assert the comment is saved in the database, checking for 'post_id' instead of 'commentable_id'
        $this->assertDatabaseHas('comments', [
            'content' => 'This is a test comment.',
            'post_id' => $post->id,  // Assert that the comment is associated with the post correctly
        ]);
    }


    public function test_comment_is_associated_with_post()
    {
        // Create a user and post
        $user = User::factory()->create();
        $post = Post::factory()->create();

        // Create a comment associated with the post
        $comment = Comment::factory()->create([
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);

        // Check if comment is associated with post
        $this->assertTrue($post->comments->contains($comment));
    }

    public function test_user_cannot_create_comment_without_authentication()
    {
        // Create a post
        $post = Post::factory()->create();

        // Try creating a comment without authentication
        $response = $this->postJson("/api/posts/{$post->id}/comments", [
            'content' => 'This is a test comment.',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    // New test to simulate the 'actingAs' method you requested
    public function test_user_can_create_comment_using_acting_as()
    {
        // Create a user and authenticate the user
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/comments', [
            'content' => 'This is a comment.',
            'post_id' => 1,  // Example Post ID, adjust accordingly
        ]);

        $response->assertStatus(200);  // Adjust the status as needed (e.g., 201 for creation)
        $response->assertJson([
            'message' => 'Comment posted and email sent', // Adjust the response as needed
        ]);

        // Assert the comment is saved in the database
        $this->assertDatabaseHas('comments', [
            'content' => 'This is a comment.',
            'commentable_id' => 1,  // Assuming the post ID is 1
            'commentable_type' => Post::class,
        ]);
    }
}
