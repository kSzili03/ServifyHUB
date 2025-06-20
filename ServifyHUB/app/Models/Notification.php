<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['notifiable_id', 'notifiable_type', 'type', 'data', 'read_at'];

    protected $casts = [
        'data' => 'array', // The data is stored as JSON and automatically cast to an array
    ];

    /**
     * Get a notification by its ID
     *
     * @param int $notificationId
     * @return Notification
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function getNotificationById(int $notificationId)
    {
        return self::findOrFail($notificationId);
    }

    /**
     * Send a comment notification
     *
     * @param int $userId
     * @param int $serviceId
     * @param string $commenterName
     * @param string $serviceName
     */
    public static function sendCommentNotification($userId, $serviceId, $commenterName, $serviceName)
    {
        // Generate the URL for the service page
        $url = route('services.show', ['service' => $serviceId]);

        // Message for the comment notification
        $message = $commenterName . ' commented on your service "' . $serviceName . '"';

        // Create the comment notification
        self::create([
            'notifiable_id' => $userId, // The user receiving the notification
            'notifiable_type' => User::class, // The model type (User)
            'type' => 'new_comment', // The type of notification
            'data' => json_encode([  // Notification data (stored as JSON)
                'message' => $message,  // The message to display
                'url' => $url,  // URL associated with the notification
                'service_id' => $serviceId,  // Add the service_id here
            ]),
        ]);
    }

    /**
     * Send a new message notification
     *
     * @param User $sender
     * @param User $receiver
     * @param string $message
     * @param int $conversationId
     */
    public static function sendMessageNotification($sender, $receiver, $message, $conversationId)
    {
        $url = route('messages.show', $conversationId);  // URL to the conversation page

        // Send notification for new message
        self::create([
            'notifiable_id' => $receiver->id,
            'notifiable_type' => User::class,
            'type' => 'new_message',
            'data' => json_encode([
                'message' => 'You have a new message from "' . $sender->name . '"',
                'url' => $url,
                'conversation_id' => $conversationId, // Include the conversation ID
            ]),
        ]);
    }

    /**
     * Send a reply notification
     *
     * @param User $sender
     * @param User $receiver
     * @param int $conversationId
     */
    public static function sendReplyNotification($sender, $receiver, $conversationId)
    {
        $url = route('messages.show', $conversationId);  // URL to the conversation page

        // Send notification for new reply
        self::create([
            'notifiable_id' => $receiver->id,
            'notifiable_type' => User::class,
            'type' => 'new_reply',
            'data' => json_encode([
                'message' => 'You have a new reply from "' . $sender->name . '"',
                'url' => $url,
                'conversation_id' => $conversationId, // Include the conversation ID
            ]),
        ]);
    }

    /**
     * Send a review notification
     *
     * @param int $userId
     * @param int $reviewerId
     * @param int $rating
     * @param string $url
     */
    public static function sendReviewNotification($userId, $reviewerId, $rating, $url)
    {
        $reviewer = User::findOrFail($reviewerId);

        $message = '"' . $reviewer->name . '" rated your profile with ' . $rating . ' stars';

        self::create([
            'notifiable_id' => $userId,
            'notifiable_type' => User::class,
            'type' => 'new_review',
            'data' => json_encode([
                'message' => $message,
                'url' => $url,
            ]),
        ]);
    }

    /**
     * Mark messages as read for the user in the conversation
     *
     * @param int $userId
     * @param int $conversationId
     */
    public static function markMessagesRead(int $userId, int $conversationId): void
    {
        // Mark messages as read
        Message::where('conversation_id', $conversationId)
            ->where('receiver_id', $userId)
            ->update(['read' => true]);

        // Fetch notifications for the user related to the conversation
        $notifications = self::where('notifiable_id', $userId)->get();

        // Iterate over each notification and mark it as read if it relates to the conversation
        foreach ($notifications as $notification) {
            $data = json_decode($notification->data, true);

            // Mark the notification as read if it relates to the conversation
            if (isset($data['conversation_id']) && $data['conversation_id'] == $conversationId) {
                $notification->update(['read_at' => now()]);
            }
        }
    }

    /**
     * Mark comments notifications as read
     *
     * @param int $userId
     * @param int $serviceId
     */
    public static function markCommentsRead($userId, $serviceId)
    {
        // Fetch notifications for the given user and service, where the type is 'new_comment'
        $notifications = self::where('notifiable_id', $userId)
            ->where('type', 'new_comment')
            ->get();

        // Iterate over the notifications and mark as read if related to the given service
        foreach ($notifications as $notification) {
            $data = json_decode($notification->data, true); // Deserialize JSON data

            // Check if the notification is related to the service
            if (isset($data['service_id']) && $data['service_id'] == $serviceId) {
                // Mark notification as read
                $notification->update(['read_at' => now()]);
            }
        }
    }

    /**
     * Mark all review notifications as read for the user
     *
     * @param int $userId
     */
    public static function markReviewsRead($userId)
    {
        // Fetch notifications for the given user and review, where the type is 'new_review'
        $notifications = self::where('notifiable_id', $userId)
            ->where('type', 'new_review')  // Filter notifications for review type
            ->get();

        // Iterate over the notifications and mark as read if related to the given reviews
        foreach ($notifications as $notification) {
            $data = json_decode($notification->data, true); // Deserialize JSON data

            // Mark notification as read
            $notification->update(['read_at' => now()]);
        }
    }
}
