<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Subcategory;

/**
 * Class CategorySeeder
 *
 * The `CategorySeeder` class is responsible for seeding the `categories` table with predefined categories
 * and seeding the `subcategories` table with corresponding subcategories for each category.
 */
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds for categories and subcategories.
     *
     * This method will populate the `categories` table with predefined categories and their associated
     * subcategories. It loops through the `categories` array and creates both categories and subcategories.
     *
     * @return void
     */
    public function run()
    {
        // Array of categories with corresponding subcategories
        $categories = [
            'IT' => ['Software Development', 'Networking', 'Cyber Security', 'Other'],
            'Building' => ['Plumbing', 'Electrical Work', 'Carpentry', 'Other'],
            'Education' => ['Tutoring', 'Online Courses', 'Language Learning', 'Other'],
            'Beauty Care' => ['Hair Care', 'Skincare', 'Nail Care', 'Makeup', 'Other'],
            'Woodworking' => ['Furniture Making', 'Handcrafted Products', 'Woodturning', 'Other'],
            'Business and Finance' => ['Financial Consulting', 'Accounting', 'Investment', 'Other'],
            'Culture and Community' => ['Wedding Planning', 'Event Organization', 'Social Work', 'Other'],
            'Entertainment' => ['Bars', 'Clubs', 'Concerts', 'Theaters', 'Other'],
            'Travel' => ['Travel Agency', 'Tourism', 'Adventure Travel', 'Other'],
            'Health and Wellness' => ['Personal Training', 'Yoga', 'Nutrition', 'Mental Health', 'Other'],
            'Technology' => ['Web Development', 'App Development', 'Cloud Computing', 'Other'],
            'Home Improvement' => ['Interior Design', 'Gardening', 'Cleaning Services', 'Other'],
            'Food and Beverage' => ['Catering', 'Baking', 'Restaurants', 'Other'],
            'Sports' => ['Fitness', 'Football', 'Tennis', 'Swimming', 'Other'],
            'Automotive' => ['Car Maintenance', 'Motorcycle Repairs', 'Car Dealerships', 'Other'],
            'Fashion' => ['Clothing', 'Jewelry', 'Footwear', 'Other'],
            'Real Estate' => ['Property Management', 'House Sales', 'Rentals', 'Other'],
            'Arts and Crafts' => ['Painting', 'Photography', 'Sculpture', 'Other'],
            'Music' => ['Guitar Lessons', 'Singing Lessons', 'DJing', 'Other'],
            'Legal Services' => ['Lawyers', 'Notary Services', 'Legal Advice', 'Other'],
            'Marketing' => ['Social Media Marketing', 'SEO', 'Content Creation', 'Other'],
            'Fitness' => ['Gym', 'Crossfit', 'Yoga', 'Other']
        ];

        // Loop through each category and create it in the database
        foreach ($categories as $categoryName => $subcategories) {
            // Create the category in the 'categories' table
            $category = Category::create(['name' => $categoryName]);

            // Loop through each subcategory and associate it with the category
            foreach ($subcategories as $subcategoryName) {
                // Create the subcategory and associate it with the created category
                Subcategory::create([
                    'name' => $subcategoryName,  // Name of the subcategory
                    'category_id' => $category->id  // Foreign key reference to the category ID
                ]);
            }
        }
    }
}
