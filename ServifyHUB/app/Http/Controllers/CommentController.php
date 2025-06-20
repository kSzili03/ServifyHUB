<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Service;

class CommentController extends Controller
{
    /**
     * Store a new comment for a service.
     *
     * @param  \App\Http\Requests\CommentRequest  $request
     * @param  int  $serviceId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CommentRequest $request, $serviceId)
    {
        // Find the service by its ID
        $service = Service::getServiceById($serviceId);

        // Create a new comment and associate it with the service
        $comment = new Comment();
        $comment->comment = $request->input('comment'); // Set the comment content
        $comment->user_id = auth()->id(); // Associate the comment with the authenticated user
        $comment->service_id = $service->id; // Link the comment to the service
        $comment->save();

        // Get the service owner's user instance
        $owner = $service->user;

        // Check if the commenter is NOT the owner of the service before sending a notification
        if ($owner->id !== auth()->id()) {
            // Send a comment notification to the service owner
            Notification::sendCommentNotification($owner->id, $service->id, auth()->user()->name, $service->name);
        }

        // Redirect back to the service page with a success message
        return redirect()->route('services.show', $service->id)->with('success', 'Comment added successfully!');
    }

    /**
     * Update an existing comment.
     *
     * @param  \App\Http\Requests\CommentRequest  $request
     * @param  int  $commentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CommentRequest $request, $commentId)
    {
        // Retrieve the comment by its ID and ensure the logged-in user owns it
        $comment = Comment::getCommentUser($commentId);

        // Update the comment content with the new data
        $comment->comment = $request->input('comment');
        $comment->save(); // Save the updated comment

        // Redirect back to the service page with a success message
        return redirect()->route('services.show', $comment->service_id)->with('success', 'Comment updated successfully!');
    }

    /**
     * Delete a comment.
     *
     * @param  int  $commentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($commentId)
    {
        // Retrieve the comment by its ID and ensure the logged-in user owns it
        $comment = Comment::getCommentUser($commentId);
        $serviceId = $comment->service_id; // Get the service ID associated with the comment

        // Delete the comment from the database
        $comment->delete();

        // Redirect back to the service page with a success message
        return redirect()->route('services.show', $serviceId)->with('success', 'Comment deleted successfully!');
    }
}
