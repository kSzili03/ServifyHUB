<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Notification;
use App\Http\Requests\MessageRequest;

class MessageController extends Controller
{
    /**
     * Display all conversations for the authenticated user.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $conversations = Message::getUserConversations(auth()->id());

        return view('auth.messages.index', compact('conversations'));
    }

    /**
     * Show a specific conversation.
     *
     * @param  int  $conversationId
     * @return \Illuminate\View\View
     */
    public function showMessages($conversationId)
    {
        $conversation = Message::getMessagesByConversationId($conversationId);

        $isSender = auth()->id() === $conversation->first()->sender_id;
        $isReceiver = auth()->id() === $conversation->first()->receiver_id;

        if ($isSender || $isReceiver) {
            // Mark both messages and notifications as read
            Notification::markMessagesRead(auth()->id(), $conversationId);
        }

        return view('auth.messages.show', compact('conversation'));
    }

    /**
     * Store a new message (including creating new conversations).
     *
     * @param  \App\Http\Requests\MessageRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MessageRequest $request)
    {
        $message = Message::createNewConversation([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'subject' => $request->subject,
        ]);

        // Send notification if the message was sent to a different user
        if ($message->sender_id !== $message->receiver_id) {
            Notification::sendMessageNotification(
                auth()->user(),
                $message->receiver,
                $message->message,
                $message->conversation_id
            );
        }

        return redirect()->route('messages.show', $message->conversation_id)->with('success', 'Message sent!');
    }

    /**
     * Reply to an existing message.
     *
     * @param  \App\Http\Requests\MessageRequest  $request
     * @param  int  $messageId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function replyMessage(MessageRequest $request, $messageId)
    {
        $originalMessage = Message::getMessageById($messageId);

        $reply = Message::createReply([
            'sender_id' => auth()->id(),
            'receiver_id' => $originalMessage->sender_id == auth()->id()
                ? $originalMessage->receiver_id
                : $originalMessage->sender_id,
            'message' => $request->message,
            'subject' => $originalMessage->subject,
            'conversation_id' => $originalMessage->conversation_id,
            'parent_message_id' => $originalMessage->id,
        ]);

        // Send notification if the reply was sent to a different user
        if ($reply->receiver_id !== auth()->id()) {
            Notification::sendReplyNotification(
                auth()->user(),
                $reply->receiver,
                $reply->conversation_id
            );
        }

        return redirect()->route('messages.show', $reply->conversation_id)->with('success', 'Reply sent!');
    }

    /**
     * Update a message's content.
     *
     * @param  \App\Http\Requests\MessageRequest  $request
     * @param  int  $messageId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateMessage(MessageRequest $request, $messageId)
    {
        $message = Message::getMessageById($messageId);

        // Ensure the logged-in user is the sender of the message
        if ($message->sender_id !== auth()->id()) {
            return redirect()->route('messages.show', $message->conversation_id)
                ->with('error', 'You are not authorized to edit this message.');
        }

        // Update the message with new content
        $message->update([
            'message' => $request->message,
        ]);

        return redirect()->route('messages.show', $message->conversation_id)
            ->with('success', 'Message updated successfully.');
    }

    /**
     * Delete a specific message.
     *
     * @param  int  $messageId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteMessage($messageId)
    {
        $message = Message::getMessageById($messageId);
        $mainMessage = Message::getMainMessageWithConversationID($message->conversation_id);

        // Check if the message belongs to the authenticated user
        if (($message->sender_id === auth()->id() || $message->receiver_id === auth()->id()) && $message->id !== $mainMessage->id) {
            $message->deleteMessage(); // Delegate the delete logic to the model
            return redirect()->route('messages.show', $message->conversation_id)
                ->with('success', 'Message deleted.');
        }

        return redirect()->route('messages.show', $message->conversation_id)
            ->with('error', 'Cannot delete the main message.');
    }

    /**
     * Delete an entire conversation.
     *
     * @param  int  $conversationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteConversation($conversationId)
    {
        $messages = Message::getMessagesByConversationId($conversationId);

        if ($messages->isEmpty()) {
            return redirect()->route('messages.index')->with('error', 'Conversation not found.');
        }

        // Ensure the user has permission to delete the conversation
        $firstMessage = $messages->first();

        if ($firstMessage->sender_id === auth()->id() || $firstMessage->receiver_id === auth()->id()) {
            // Pass the entire list of messages to deleteConversation
            $firstMessage->deleteConversation($messages);  // Pass the messages to the deleteConversation method
            return redirect()->route('messages.index')->with('success', 'Conversation deleted.');
        }

        return redirect()->route('messages.show', $conversationId)
            ->with('error', 'You are not authorized to delete this conversation.');
    }
}
