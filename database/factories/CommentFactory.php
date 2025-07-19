<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'content' => $this->faker->sentence,  // content field
            'user_id' => User::factory(),          // associate a user
            'commentable_type' => Post::class,     // associate with a Post
            'commentable_id' => Post::factory(),  // create a post for the comment
            'post_id' => Post::factory(),
        ];
    }
}
