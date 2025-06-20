<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with the latest 6 services and top 5 most viewed services.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the latest 6 services
        $latestServices = Service::getLatestServices(6);

        // Get the top 5 most viewed services
        $topViewedServices = Service::getTopViewedServices(5);

        // Get all categories
        $categories = Category::all();

        // Return the home view with the services and categories data
        return view('home', compact('latestServices', 'topViewedServices', 'categories'));
    }

    /**
     * Display the login page.
     *
     * @return \Illuminate\View\View
     */
    public function login()
    {
        // Return the login view
        return view('login');
    }

    /**
     * Display the registration page.
     *
     * @return \Illuminate\View\View
     */
    public function register()
    {
        // Return the registration view
        return view('register');
    }

    /**
     * Display all services with optional filtering by category, subcategory, and price.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function services(Request $request)
    {
        // Get all categories along with their subcategories
        $categories = Category::getCategoriesWithSubcategories();

        // Get unique locations (for multi-selection)
        $locations = Service::getDistinctLocations();

        // Initial query for services with filtering
        $services = Service::filterServices(Service::query(), $request)->paginate(5);

        // Return the services view with the filtered services and categories data
        return view('services.services', compact('services', 'categories', 'locations'));
    }
}
