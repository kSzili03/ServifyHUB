<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateReferencesTable
 *
 * This migration creates the `references` table in the database.
 * The table stores references created by users, each containing a title,
 * description, and optionally an image path.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This method creates the `references` table, which stores references
     * created by users. Each reference has a title, description, and an optional image.
     *
     * @return void
     */
    public function up(): void
    {
        // Create the 'references' table
        Schema::create('references', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key for the reference
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key for the user who created the reference (references 'users' table)
            $table->string('title'); // Title of the reference
            $table->text('description'); // Detailed description of the reference
            $table->string('image_path')->nullable(); // Optional image path for the reference (nullable)
            $table->timestamps(); // Automatically creates 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method will drop the `references` table if it exists.
     * It is used when rolling back the migration.
     *
     * @return void
     */
    public function down(): void
    {
        // Drop the 'references' table if it exists
        Schema::dropIfExists('references');
    }
};
