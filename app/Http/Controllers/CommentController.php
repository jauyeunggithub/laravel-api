<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment on a post.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Post $post)
    {
        // Validate the comment input
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        // Create the comment and associate it with the post
        $comment = Comment::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'post_id' => $post->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
        ]);

        return response()->json([
            'message' => 'Comment posted and email sent',
            'data' => $comment
        ], 200);
    }
}
