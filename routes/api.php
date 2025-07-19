<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Comment;
use App\Mail\CommentReceived;
use Illuminate\Support\Facades\Mail;


Route::post('register', function () {
    $validated = request()->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|confirmed|min:8',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    return response()->json([
        'data' => $user,
        'token' => $user->createToken('API Token')->plainTextToken,
    ], 201);
});

Route::post('login', function () {
    $credentials = request(['email', 'password']);

    if (Auth::attempt($credentials)) {
        return response()->json([
            'token' => Auth::user()->createToken('API Token')->plainTextToken,
        ]);
    }

    return response()->json(['message' => 'Unauthorized'], 401);
});

Route::middleware('auth:sanctum')->post('/posts/{post}/comments', function (Post $post) {
    $comment = new Comment();
    $comment->comment = request('comment');
    $post->comments()->save($comment);

    // Send the email
    Mail::to('admin@example.com')->send(new CommentReceived($comment));

    return response()->json(['message' => 'Comment posted and email sent']);
});
