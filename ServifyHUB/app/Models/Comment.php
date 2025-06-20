<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // The fillable fields for the comment
    protected $fillable = ['user_id', 'service_id', 'comment'];

    // Relationship to the service
    public function service()
    {
        return $this->belongsTo(Service::class); // A comment belongs to a service
    }

    // Relationship to the user who made the comment
    public function user()
    {
        return $this->belongsTo(User::class); // A comment belongs to a user
    }

    /**
     * Get the user who made a specific comment.
     *
     * @param int $commentId
     * @return \App\Models\Comment
     */
    public static function getCommentUser($commentId)
    {
        return self::with('user')->findOrFail($commentId); // Fetch comment with user information
    }
}
