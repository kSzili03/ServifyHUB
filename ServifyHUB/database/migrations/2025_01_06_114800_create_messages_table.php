<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateMessagesTable
 *
 * This migration creates the `messages` table in the database.
 * The table stores messages between users, including the message content, sender, receiver, subject,
 * whether the message is a reply, and other relevant details.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This method creates the `messages` table, which stores messages exchanged between users.
     * It defines relationships between the sender and receiver, allows tracking if the message has been read,
     * supports message replies, and organizes messages into conversations.
     *
     * @return void
     */
    public function up(): void
    {
        // Create the 'messages' table
        Schema::create('messages', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key for the message
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); // Foreign key referencing the 'users' table for the sender
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade'); // Foreign key referencing the 'users' table for the receiver
            $table->text('message'); // The message content (can be long text)
            $table->string('subject')->nullable(); // Subject of the message (nullable initially)
            $table->boolean('is_reply')->default(false); // Indicates if the message is a reply to another message (default is false)
            $table->foreignId('parent_message_id')->nullable()->constrained('messages')->onDelete('cascade'); // For reply messages, reference to the parent message
            $table->foreignId('conversation_id')->nullable()->constrained('messages')->onDelete('cascade'); // ID for the conversation to which the message belongs
            $table->boolean('read')->default(false); // To track if the message has been read (default is false)
            $table->timestamps(); // Automatically creates 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method will drop the `messages` table if it exists.
     * It is used when rolling back the migration.
     *
     * @return void
     */
    public function down(): void
    {
        // Drop the 'messages' table if the migration is rolled back
        Schema::dropIfExists('messages');
    }
};
