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
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $post = Post::factory()->create();
        $response = $this->postJson("/api/posts/{$post->id}/comments", [
            'comment' => 'This is a test comment',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Comment posted and email sent']);
        $this->assertCount(1, $post->comments);
    }
}
