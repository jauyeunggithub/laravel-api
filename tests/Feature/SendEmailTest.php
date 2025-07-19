<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_email()
    {
        // Create a user and post
        $user = User::factory()->create();
        $post = Post::factory()->create();

        // Create a comment using the factory and associate it with the post
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);

        // Simulate sending the email (you may have a mailable class to send the email)
        Mail::fake();
        Mail::to('admin@example.com')->send(new \App\Mail\CommentReceived($comment));

        // Assert that the mail was sent
        Mail::assertSent(\App\Mail\CommentReceived::class);
    }
}
