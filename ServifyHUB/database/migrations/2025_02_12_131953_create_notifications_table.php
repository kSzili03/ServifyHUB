<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateNotificationsTable
 *
 * This migration creates the `notifications` table in the database.
 * The table stores notifications that can be related to various models.
 * Each notification has a type, associated data, and a read timestamp.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This method creates the `notifications` table, which is used to store
     * notifications that can be linked to different models via the `notifiable` polymorphic relationship.
     * Notifications contain a type, data, and an optional read timestamp.
     *
     * @return void
     */
    public function up(): void
    {
        // Create the 'notifications' table
        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key for each notification
            $table->morphs('notifiable'); // Creates 'notifiable_id' and 'notifiable_type' columns to link to any model (polymorphic relation)
            $table->string('type'); // The type of the notification (e.g., email, SMS, system message)
            $table->text('data'); // The notification data (can be stored as JSON or serialized format)
            $table->timestamp('read_at')->nullable(); // Timestamp for when the notification was read (nullable if not yet read)
            $table->timestamps(); // Automatically creates 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method will drop the `notifications` table if it exists.
     * It is used when rolling back the migration.
     *
     * @return void
     */
    public function down(): void
    {
        // Drop the 'notifications' table if it exists
        Schema::dropIfExists('notifications');
    }
};
