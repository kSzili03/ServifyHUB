<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 *
 * The `DatabaseSeeder` class is responsible for running all the individual seeders
 * that populate the database with initial data. It acts as the main entry point
 * for executing multiple seeders, like `CategorySeeder`, `UserSeeder`, and `ServiceSeeder`.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This method is executed when running `php artisan db:seed` command.
     * It calls other individual seeders (e.g., `CategorySeeder`, `UserSeeder`, `ServiceSeeder`)
     * to populate the respective database tables with data.
     *
     * @return void
     */
    public function run(): void
    {
        // Calling other seeders for categories, users, and services.
        $this->call([
            CategorySeeder::class,  // Seeds the categories table with initial data
            UserSeeder::class,      // Seeds the users table with initial data
            ServiceSeeder::class,   // Seeds the services table with initial data
        ]);
    }
}
