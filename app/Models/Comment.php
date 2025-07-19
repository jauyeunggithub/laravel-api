<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',  // Make sure 'content' is fillable
        'user_id',  // Add 'user_id' here for mass assignment
        'commentable_id',
        'commentable_type',
        'post_id',
    ];

    // Optionally, if you're using the comment's relationships:
    public function commentable()
    {
        return $this->morphTo();
    }
}
