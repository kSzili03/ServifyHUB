<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Notification;
use App\Models\Review;
use App\Models\User;

class ReviewController extends Controller
{
    /**
     * Store a new review for a user.
     *
     * @param  ReviewRequest  $request
     * @param  int  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReviewRequest $request, $userId)
    {
        // Check if the logged-in user has already reviewed the target user
        if (Review::userHasReviewed(auth()->id(), $userId)) {
            // If the user has already reviewed, redirect with an error message
            return redirect()->route('users.profile', $userId)
                ->with('error', 'You have already reviewed this user.');
        }

        // The request data is already validated through the ReviewRequest
        $validatedData = $request->validated();

        // Create a new review instance
        $review = new Review();
        $review->user_id = auth()->id(); // The currently authenticated user is the reviewer
        $review->reviewed_user_id = $userId; // The target user being reviewed
        $review->rating = $validatedData['rating']; // The rating given by the reviewer
        $review->review = $validatedData['comment']; // The optional review comment
        $review->save(); // Save the review to the database

        // Retrieve the user being reviewed and update their average rating
        $user = User::findOrFail($userId); // Fetch the user being reviewed
        $user->updateAverageRating(); // Update the user's average rating (assumed custom method)

        // Generate the URL to the reviewed user's profile
        $url = route('users.profile', $userId);

        // Create a new notification for the reviewed user about the new review
        Notification::sendReviewNotification($userId, auth()->id(), $validatedData['rating'], $url);

        // Redirect back to the reviewed user's profile page with a success message
        return redirect()->route('users.profile', $userId)->with('success', 'Review submitted successfully!');
    }
}
