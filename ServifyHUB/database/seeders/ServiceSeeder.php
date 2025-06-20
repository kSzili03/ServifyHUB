<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\User;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Str;

/**
 * Class ServiceSeeder
 *
 * This seeder class is responsible for populating the 'services' table with
 * predefined data by associating each service with a user, category, subcategory,
 * location, and other relevant information. Each user will be assigned between
 * 5 and 10 services, each with random attributes.
 */
class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This method will be executed when running the `php artisan db:seed` command.
     * It assigns a set of services to each user, each service having a random
     * category, subcategory, location, and associated information like price,
     * description, contact, and more.
     *
     * @return void
     */
    public function run(): void
    {
        // Fetch all users, categories, and subcategories from the database
        $users = User::all(); // All users will be retrieved to associate services with them
        $categories = Category::all(); // All categories will be retrieved to assign a category to each service
        $subcategories = Subcategory::all(); // All subcategories will be retrieved to assign a subcategory to each service

        // Array of predefined cities and coordinates
        $locations = [
            ['name' => 'Odorheiu Secuiesc', 'latitude' => 46.383333, 'longitude' => 25.358333],
            ['name' => 'Miercurea Ciuc', 'latitude' => 46.362500, 'longitude' => 25.784167],
            ['name' => 'Vlahita', 'latitude' => 46.309722, 'longitude' => 25.408333],
            ['name' => 'Capalnita', 'latitude' => 46.387222, 'longitude' => 25.845833],
            ['name' => 'Targu Mures', 'latitude' => 46.541667, 'longitude' => 24.589167],
            ['name' => 'Targu Secuiesc', 'latitude' => 45.860833, 'longitude' => 26.087222],
            // You can add more cities here as needed
        ];

        // Define the image paths for each category (without "http://localhost/storage/" prefix)
        $categoryImages = [
            'IT' => 'categories/it.jpg',
            'Building' => 'categories/building.jpg',
            'Education' => 'categories/education.jpg',
            'Beauty Care' => 'categories/beauty.jpg',
            'Woodworking' => 'categories/woodworking.jpg',
            'Entertainment' => 'categories/entertainment.jpg',
            'Culture and Community' => 'categories/community.jpg',
            'Business and Finance' => 'categories/business.jpg',
            'Travel' => 'categories/travel.jpg',
            'Health and Wellness' => 'categories/health.jpg',
            'Technology' => 'categories/technology.jpg',
            'Home Improvement' => 'categories/home.jpg',
            'Food and Beverage' => 'categories/food.jpg',
            'Sports' => 'categories/sport.jpg',
            'Automotive' => 'categories/automotive.jpg',
            'Fashion' => 'categories/fashion.jpg',
            'Real Estate' => 'categories/estate.jpg',
            'Arts and Crafts' => 'categories/art.jpg',
            'Music' => 'categories/music.jpg',
            'Legal Services' => 'categories/legal.jpg',
            'Marketing' => 'categories/marketing.jpg',
            'Fitness' => 'categories/fitness.jpg',
        ];

        // Loop through each user to assign services
        foreach ($users as $user) {
            // Create an array to track assigned services for the user
            $assignedServices = [];

            // Assign between 5 to 10 services to each user
            $servicesToAssignCount = rand(5, 10);  // Randomly decide how many services the user will have

            // Loop to create the required number of services for the user
            for ($i = 0; $i < $servicesToAssignCount; $i++) {
                // Randomly choose a category from the available categories
                $category = $categories->random();

                // Get a random subcategory from the current category
                $subcategory = $subcategories->where('category_id', $category->id)->random();

                // Randomly select a location from the predefined locations
                $location = $locations[array_rand($locations)];

                // Generate a unique service name and description
                $uniqueSuffix = Str::random(5);  // Generate a random string to make the service name unique
                $serviceName = $category->name . ' ' . $subcategory->name . ' Service ' . $uniqueSuffix;
                $serviceDescription = 'This service offers high-quality solutions in ' . $category->name . ' (' . $subcategory->name . ').';

                // Determine the service picture URL based on the category name
                $serviceImage = $categoryImages[$category->name] ?? 'placeholder.svg'; // Default to 'placeholder.svg' if category image is missing

                // Create and save the service in the database
                $service = Service::create([
                    'name' => $serviceName,  // Name of the service
                    'description' => $serviceDescription,  // Description of the service
                    'price' => rand(1000, 10000) / 100,  // Random price between 10 and 100 dollars (precision to 2 decimal places)
                    'contact' => 'contact@' . Str::random(5) . '.com',  // Random contact email for the service
                    'service_picture' => $serviceImage,  // Service image URL determined by the category
                    'views' => rand(1, 100),  // Random view count between 1 and 100
                    'user_id' => $user->id,  // Associate this service with the current user
                    'category_id' => $category->id,  // Associate this service with a category
                    'subcategory_id' => $subcategory->id,  // Associate this service with a subcategory
                    'latitude' => $location['latitude'],  // Latitude from the randomly chosen location
                    'longitude' => $location['longitude'],  // Longitude from the randomly chosen location
                    'city' => $location['name'],  // City name from the selected location
                ]);

                // Add the newly created service ID to the assigned services array
                $assignedServices[] = $service->id;
            }
        }
    }
}
