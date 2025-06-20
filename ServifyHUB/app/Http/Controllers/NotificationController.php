<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mark a single notification as read.
     *
     * @param  int  $notificationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead($notificationId)
    {
        // Find the notification by ID
        $notification = Notification::getNotificationById($notificationId);

        // Update the 'read_at' timestamp to mark the notification as read
        $notification->update(['read_at' => now()]);

        // Get the URL associated with the notification
        $data = json_decode($notification->data, true);
        $url = $data['url'] ?? route('home');  // Default to home if no URL is provided

        // Redirect to the URL associated with the notification
        return redirect($url);
    }

    /**
     * Mark all notifications as read.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead()
    {
        // Mark all notifications for the authenticated user as read
        Auth::user()->notifications()->update(['read_at' => now()]);  // Update the read_at timestamp

        // Return back to the previous page
        return back();
    }

    /**
     * Delete all notifications for the authenticated user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAll()
    {
        // Delete all notifications for the authenticated user
        Auth::user()->notifications()->delete();

        // Return back to the previous page
        return back();
    }
}
