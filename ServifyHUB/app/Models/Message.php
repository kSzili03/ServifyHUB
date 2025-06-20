<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id', 'receiver_id', 'message',
        'is_reply', 'parent_message_id',
        'conversation_id', 'subject', 'read'
    ];

    // Sender relationship
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Receiver relationship
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Replies to the original message
    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_message_id');
    }

    // Get the conversation
    public function conversation()
    {
        return $this->belongsTo(Message::class, 'conversation_id');
    }

    /**
     * Get a message by its ID.
     *
     * @param int $id
     * @return Message
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function getMessageById($id)
    {
        return self::findOrFail($id);
    }

    /**
     * Get the main message (the message that is not a reply) for a given conversation.
     *
     * @param int $conversationId
     * @return Message
     */
    public static function getMainMessageWithConversationID($conversationId)
    {
        return self::where('conversation_id', $conversationId)
            ->where('is_reply', false) // Main message is not a reply
            ->first(); // Get the first message that is not a reply
    }

    /**
     * Get all messages in a conversation.
     *
     * @param int $conversationId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getMessagesByConversationId($conversationId)
    {
        return self::where('conversation_id', $conversationId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Fetch grouped conversations for a user.
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public static function getUserConversations(int $userId)
    {
        return self::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('conversation_id');
    }

    /**
     * Create a new conversation.
     *
     * @param array $data
     * @return Message
     */
    public static function createNewConversation(array $data): self
    {
        $message = self::create([
            'sender_id' => $data['sender_id'],
            'receiver_id' => $data['receiver_id'],
            'message' => $data['message'],
            'subject' => $data['subject'] ?? null,
            'is_reply' => false,
        ]);

        $message->update(['conversation_id' => $message->id]);

        return $message;
    }

    /**
     * Reply to a conversation.
     *
     * @param array $data
     * @return Message
     */
    public static function createReply(array $data): self
    {
        return self::create([
            'sender_id' => $data['sender_id'],
            'receiver_id' => $data['receiver_id'],
            'message' => $data['message'],
            'subject' => $data['subject'] ?? null,
            'is_reply' => true,
            'conversation_id' => $data['conversation_id'],
            'parent_message_id' => $data['parent_message_id'] ?? null,
        ]);
    }

    /**
     * Delete a message.
     *
     * @return void
     */
    public function deleteMessage()
    {
        $this->delete();
    }

    /**
     * Delete all messages in a conversation.
     *
     * @param \Illuminate\Database\Eloquent\Collection $messages
     * @return void
     */
    public function deleteConversation($messages)
    {
        foreach ($messages as $message) {
            $message->delete();  // Delete each message in the conversation
        }
    }
}
