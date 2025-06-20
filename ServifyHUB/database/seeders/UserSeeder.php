<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserSeeder
 *
 * This seeder class is responsible for populating the 'users' table
 * with a predefined set of user data. Each user entry consists of
 * a name, email, and a hashed password.
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This method will be executed when running the `php artisan db:seed` command.
     * It defines an array of user data and inserts each entry into the 'users' table.
     *
     * @return void
     */
    public function run(): void
    {
        // Define an array of users with their details
        $users = [
            ['username' => 'John Smith', 'email' => 'john.smith@example.com', 'password' => 'Password123!'],
            ['username' => 'Emily Johnson', 'email' => 'emily.johnson@example.com', 'password' => 'SecurePass456!'],
            ['username' => 'Michael Brown', 'email' => 'michael.brown@example.com', 'password' => 'Brownie789!'],
            ['username' => 'Jessica Williams', 'email' => 'jessica.williams@example.com', 'password' => 'JessieStrong!'],
            ['username' => 'Daniel Miller', 'email' => 'daniel.miller@example.com', 'password' => 'Daniel987!'],
            ['username' => 'Sophia Davis', 'email' => 'sophia.davis@example.com', 'password' => 'SophiaRocks!'],
            ['username' => 'Matthew Wilson', 'email' => 'matthew.wilson@example.com', 'password' => 'MattyPass123!'],
            ['username' => 'Olivia Martinez', 'email' => 'olivia.martinez@example.com', 'password' => 'OliMartinez!'],
            ['username' => 'James Anderson', 'email' => 'james.anderson@example.com', 'password' => 'JamesSecure#'],
            ['username' => 'Emma Thomas', 'email' => 'emma.thomas@example.com', 'password' => 'EmmaPass432!'],
        ];

        /**
         * Loop through the users array and create a new User record in the database
         * for each user in the array.
         */
        foreach ($users as $userData) {
            // Create a new user record
            User::create([
                'name' => $userData['username'], // The 'username' will be stored in the 'name' field of the User table
                'email' => $userData['email'],   // The email is stored directly in the 'email' field
                'password' => Hash::make($userData['password']), // Hash the password before storing it for security
            ]);
        }
    }
}
