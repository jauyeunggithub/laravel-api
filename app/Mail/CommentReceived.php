<?php

namespace App\Mail;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommentReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $content;

    public function __construct(Comment $content)
    {
        $this->content = $content;
    }

    public function build()
    {
        return $this->subject('New Comment Received')
                    ->view('emails.comment-received');
    }
}
