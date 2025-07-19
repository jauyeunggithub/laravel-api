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
        $response = $this->actingAs($user)->postJson('/api/posts/1/comments', [
            'content' => 'This is a test comment',
        ]);

        // Assert that the comment was successfully posted and email sent
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Comment posted and email sent']);

        // Optionally, you can also check if the post now has one comment
        $post = Post::find(1); // Make sure the post with ID 1 exists
        $this->assertCount(1, $post->comments);

        // Optionally, check the comment's content in the database
        $this->assertDatabaseHas('comments', [
            'content' => 'This is a test comment',
            'commentable_id' => 1,  // Assuming the post ID is 1
            'commentable_type' => Post::class,
        ]);
    }
}
