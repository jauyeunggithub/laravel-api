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

        // Create a comment on the post
        $response = $this->postJson("/api/posts/{$post->id}/comments", [
            'comment' => 'This is a test comment.',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Comment posted and email sent',
        ]);

        // Assert the comment is saved
        $this->assertDatabaseHas('comments', [
            'comment' => 'This is a test comment.',
            'commentable_type' => Post::class,
            'commentable_id' => $post->id,
        ]);
    }

    public function test_comment_is_associated_with_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);

        // Check if comment is associated with post
        $this->assertTrue($post->comments->contains($comment));
    }

    public function test_user_cannot_create_comment_without_authentication()
    {
        $post = Post::factory()->create();

        // Try creating a comment without authentication
        $response = $this->postJson("/api/posts/{$post->id}/comments", [
            'comment' => 'This is a test comment.',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated']);
    }
}
