<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateServicesTable
 *
 * This migration creates the `services` table in the database.
 * The table stores details for various services, including their name, description, price, and other metadata.
 * It also contains foreign keys linking the service to the user who created it, its category, and its subcategory.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This method will create the `services` table, which contains details about various services.
     *
     * @return void
     */
    public function up(): void
    {
        // Create the 'services' table
        Schema::create('services', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key for the service
            $table->string('name'); // Column for storing the name of the service
            $table->longText('description'); // Column for storing the description of the service
            $table->decimal('price', 10, 2); // Column for storing the price of the service, up to 10 digits with 2 decimal places
            $table->string('contact'); // Column for storing contact information for the service provider
            $table->string('service_picture')->nullable(); // Column to store the service's image file name, nullable
            $table->integer('views')->default(0); // Column for storing the number of views, defaults to 0
            $table->timestamps(); // Automatically creates 'created_at' and 'updated_at' columns
            $table->decimal('latitude', 10, 8)->nullable(); // Latitude for the service location, nullable
            $table->decimal('longitude', 11, 8)->nullable(); // Longitude for the service location, nullable
            $table->string('city')->nullable(); // City where the service is available, nullable

            // Foreign key to the 'users' table, the user who created the service
            $table->foreignId('user_id')->constrained();

            // Foreign key to the 'categories' table, the category to which the service belongs
            $table->foreignId('category_id')->constrained()->onDelete('cascade');

            // Foreign key to the 'subcategories' table, the subcategory to which the service belongs
            $table->foreignId('subcategory_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method will drop the `services` table if it exists.
     * It is used when rolling back the migration.
     *
     * @return void
     */
    public function down(): void
    {
        // Drop the 'services' table if it exists
        Schema::dropIfExists('services');
    }
};
