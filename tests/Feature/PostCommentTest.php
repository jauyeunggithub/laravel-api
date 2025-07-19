<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_comment()
    {
        // Create and authenticate the user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create a post for testing
        $post = Post::factory()->create();

        // Make the request to post a comment
        $response = $this->postJson('/api/posts/' . $post->id . '/comments', [
            'content' => 'This is a test comment',
        ]);

        // Assert that the comment was successfully posted
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Comment posted and email sent']);

        // Optionally, check if the post now has one comment
        $post->refresh();
        $this->assertCount(1, $post->comments);

        // Optionally, check the comment's content in the database
        $this->assertDatabaseHas('comments', [
            'content' => 'This is a test comment',
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);
    }
}
