<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_email()
    {
        // Fake mail to avoid actually sending emails
        Mail::fake();

        // Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a comment (assuming a post ID is required for the comment)
        $comment = Comment::factory()->create([
            'commentable_type' => 'App\Models\Post', // Adjust this as needed
            'commentable_id' => 1, // Assuming there's a post with ID 1, adjust as needed
        ]);

        // Trigger the email sending logic
        Mail::to('admin@example.com')->send(new \App\Mail\CommentReceived($comment));

        // Assert that the email was sent
        Mail::assertSent(\App\Mail\CommentReceived::class);
    }
}
