<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = ['user_id', 'rating', 'review', 'reviewed_user_id'];

    /**
     * Define the relationship with the User model (the reviewer)
     *
     * A review belongs to the user who gave it
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // A review belongs to the user who gave it
    }

    /**
     * Define the relationship with the reviewed user
     *
     * A review belongs to the user who is being reviewed
     */
    public function reviewedUser()
    {
        return $this->belongsTo(User::class, 'reviewed_user_id'); // A review belongs to the user who is being reviewed
    }

    /**
     * Check if a user has already reviewed another user
     *
     * @param int $userId
     * @param int $reviewedUserId
     * @return bool
     */
    public static function userHasReviewed($userId, $reviewedUserId)
    {
        // Check if a review already exists from the user to the reviewed user
        return self::where('user_id', $userId)
            ->where('reviewed_user_id', $reviewedUserId) // Ensure the review is for the correct user pair
            ->exists(); // Return true if the review exists, otherwise false
    }

}
