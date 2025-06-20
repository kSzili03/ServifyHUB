<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCategoriesAndSubcategoriesTables
 *
 * This migration creates two tables:
 * - `categories`: Stores different categories for services.
 * - `subcategories`: Stores subcategories for each category.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This method creates the `categories` and `subcategories` tables in the database.
     * The `subcategories` table has a foreign key relationship with the `categories` table.
     *
     * @return void
     */
    public function up()
    {
        // Create the 'categories' table
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key (unsignedBigInteger)
            $table->string('name')->unique(); // Column for storing category name, unique constraint
            $table->timestamps(); // Automatically creates 'created_at' and 'updated_at' columns
        });

        // Create the 'subcategories' table
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name'); // Column for storing subcategory name
            $table->foreignId('category_id') // Foreign key to link subcategory with a category
            ->constrained() // Automatically references the 'categories' table
            ->onDelete('cascade'); // If a category is deleted, all related subcategories are also deleted
            $table->timestamps(); // Automatically creates 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method drops the 'subcategories' and 'categories' tables if they exist.
     * It is used when rolling back the migration.
     *
     * @return void
     */
    public function down()
    {
        // Drop the 'subcategories' table first to avoid foreign key constraint issues
        Schema::dropIfExists('subcategories');

        // Drop the 'categories' table
        Schema::dropIfExists('categories');
    }
};
