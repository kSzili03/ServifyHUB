<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateReviewsTable
 *
 * This migration creates the `reviews` table in the database.
 * The table stores reviews given by users to other users, including ratings, review text, and the users involved.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This method will create the `reviews` table, which contains reviews from users.
     * It stores details about the reviewer, the reviewed user, the rating, and the review text.
     *
     * @return void
     */
    public function up(): void
    {
        // Create the 'reviews' table
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key for the review
            $table->unsignedBigInteger('user_id'); // Foreign key for the user who gave the review
            $table->unsignedBigInteger('reviewed_user_id'); // Foreign key for the user who is being reviewed
            $table->float('rating', 2, 1); // Rating on a scale (for example: 1-5 scale, with one decimal point)
            $table->text('review')->nullable(); // Optional review text (nullable)
            $table->timestamps(); // Automatically creates 'created_at' and 'updated_at' columns

            // Foreign key relationship for the reviewer (user_id references users table)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Foreign key relationship for the reviewed user (reviewed_user_id references users table)
            $table->foreign('reviewed_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method will drop the `reviews` table if it exists.
     * It is used when rolling back the migration.
     *
     * @return void
     */
    public function down(): void
    {
        // Drop the 'reviews' table if it exists
        Schema::dropIfExists('reviews');
    }
};
