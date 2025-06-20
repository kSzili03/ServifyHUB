<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    // Use HasFactory for model factory and Notifiable for notifications
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'average_rating'];

    protected $hidden = [
        'password',     // Hide password for security
        'remember_token', // Hide remember_token for security
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',            // Ensure password is hashed for storage
        ];
    }

    /**
     * Update the average rating for the user.
     * This method calculates the average rating from the reviews the user has received.
     */
    public function updateAverageRating()
    {
        // Calculate the average rating from all reviews of the user
        $averageRating = $this->reviews()->avg('rating');

        // Round the average to one decimal place and save it
        $this->average_rating = round($averageRating, 1);
        $this->save();

        // Optionally, cache the average rating to improve performance
        Cache::put('user_' . $this->id . '_average_rating', $this->average_rating, now()->addMinutes(10));
    }

    /**
     * Relationship with reviews.
     * The user has many reviews where they are the 'reviewed_user_id'.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewed_user_id');
    }

    /**
     * Relationship with services.
     * The user can have many services they offer.
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Relationship with sent messages.
     * The user can send many messages.
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Relationship with received messages.
     * The user can receive many messages.
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Relationship with replies.
     * The user can have many replies to their messages.
     */
    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_message_id');
    }

    /**
     * Relationship with references.
     * The user can have many references.
     */
    public function references()
    {
        return $this->hasMany(Reference::class);
    }

    /**
     * Get user by ID
     *
     * @param int $userId
     * @return \App\Models\User
     */
    public static function getUserById(int $userId)
    {
        return self::findOrFail($userId); // Find the service by ID or fail if not found
    }

    /**
     * Handle the profile picture upload for the user.
     * If a new profile picture is uploaded, it replaces the old one.
     */
    public static function handleProfilePicture($request, $user)
    {
        if ($request->hasFile('profile_picture')) {
            // Delete the old profile picture if it exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store the new profile picture
            $file = $request->file('profile_picture');
            $path = $file->store('profile_pictures', 'public');
            return $path;
        }

        return $user->profile_picture;
    }

    /**
     * Delete the profile picture for the user.
     * This will also remove the image file from storage.
     */
    public static function deleteProfilePicture($user)
    {
        if ($user->profile_picture) {
            // Delete the image from storage
            Storage::disk('public')->delete($user->profile_picture);

            // Set the profile picture field to null
            $user->profile_picture = null;
        }
    }
}
