<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateUsersTable
 *
 * This migration is responsible for creating the `users` table in the database.
 * It defines the structure of the table, including all columns and their properties.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This method will create the `users` table in the database. The table contains fields
     * for user details like name, email, password, profile picture, and average rating.
     *
     * @return void
     */
    public function up(): void
    {
        // Create the 'users' table
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key for user (default 'id' column)
            $table->string('name'); // Column for storing the user's name
            $table->string('email')->unique(); // Column for storing the user's unique email address
            $table->string('password'); // Column for storing the user's password (hashed)
            $table->string('profile_picture')->nullable(); // Optional profile picture field, can be null
            $table->decimal('average_rating', 3, 2)->default(0); // User's average rating, default is 0
            $table->rememberToken(); // Token used for "remember me" functionality during login
            $table->timestamps(); // Automatically adds 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method will drop the `users` table if it exists, rolling back the migration.
     *
     * @return void
     */
    public function down(): void
    {
        // Drop the 'users' table if it exists
        Schema::dropIfExists('users');
    }
};
