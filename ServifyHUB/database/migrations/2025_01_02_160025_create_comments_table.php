<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCommentsTable
 *
 * This migration creates the `comments` table in the database.
 * The table stores comments made by users on services, linking each comment to a user and a service.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This method creates the `comments` table, which stores comments made by users
     * on services. It also defines foreign key relationships with the `users` and `services` tables.
     *
     * @return void
     */
    public function up(): void
    {
        // Create the 'comments' table
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key for the comment
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key for the user who made the comment (references 'users' table)
            $table->foreignId('service_id')->constrained()->onDelete('cascade'); // Foreign key for the service related to the comment (references 'services' table)
            $table->text('comment'); // Column for storing the actual comment text
            $table->timestamps(); // Automatically creates 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method will drop the `comments` table if it exists.
     * It is used when rolling back the migration.
     *
     * @return void
     */
    public function down(): void
    {
        // Drop the 'comments' table if it exists
        Schema::dropIfExists('comments');
    }
};
