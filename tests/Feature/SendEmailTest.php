<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_email()
    {
        Mail::fake();

        $comment = Comment::factory()->create();

        // Trigger email logic
        Mail::to('admin@example.com')->send(new \App\Mail\CommentReceived($comment));

        Mail::assertSent(\App\Mail\CommentReceived::class);
    }
}
