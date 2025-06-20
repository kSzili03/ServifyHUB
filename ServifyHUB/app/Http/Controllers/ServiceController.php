<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Service;

class ServiceController extends Controller
{
    /**
     * Show a single service by its ID.
     *
     * @param  int  $serviceId
     * @return \Illuminate\View\View
     */
    public function show($serviceId)
    {
        // Fetch the service based on its ID
        $service = Service::getServiceById($serviceId);
        $user = $service->user; // Get the user associated with this service

        // Increment view count each time the service is viewed
        $service->increment('views');

        // Get all notifications for the authenticated user related to this service
        Notification::markCommentsRead(auth()->id(), $serviceId);

        // Return the service view with the service and user data
        return view('services.show', compact('service', 'user'));
    }

    /**
     * Show the form to create a new service.
     *
     * @return \Illuminate\View\View
     */
    public function createService()
    {
        // Fetch predefined categories and pass them to the view
        $categories = Category::getCategoriesWithSubcategories();
        return view('auth.services.create', compact('categories'));
    }

    /**
     * Handle the creation of a new service.
     *
     * @param  ServiceRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createServicePost(ServiceRequest $request)
    {
        // Retrieve validated data from the request
        $validatedData = $request->validated();

        // Clean input data to remove any unwanted HTML tags
        $validatedData['name'] = strip_tags($validatedData['name']);
        $validatedData['description'] = strip_tags($validatedData['description']);
        $validatedData['contact'] = strip_tags($validatedData['contact']);
        $validatedData['price'] = (float) number_format($validatedData['price'], 2, '.', '');
        $validatedData['user_id'] = auth()->id();
        $validatedData['city'] = strip_tags($validatedData['city']);

        // Handle file upload if exists
        $validatedData['service_picture'] = Service::addServicePicture($request);

        // Create a new service using the validated data
        $service = new Service();
        $service->name = $validatedData['name'];
        $service->description = $validatedData['description'];
        $service->category_id = $validatedData['category_id'];
        $service->subcategory_id = $validatedData['subcategory_id'];  // Ensure this is correctly set
        $service->price = $validatedData['price'];
        $service->contact = $validatedData['contact'];
        $service->user_id = $validatedData['user_id'];
        $service->service_picture = $validatedData['service_picture'] ?? null;
        $service->latitude = $validatedData['latitude'];
        $service->longitude = $validatedData['longitude'];
        $service->city = $validatedData['city'];

        // Save the service and return response
        if ($service->save()) {
            return redirect()->route('services.show', $service->id)->with('success', 'Service created successfully!');
        }

        return redirect()->route('services.create')->with('error', 'Failed to create the service.');
    }

    /**
     * Show the form to edit an existing service.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function editService($id)
    {
        // Fetch the service by its ID and ensure it's owned by the authenticated user
        $service = Service::where('user_id', auth()->id())->findOrFail($id);

        // Fetch categories and subcategories to display in the edit form
        $categories = Category::with('subcategories')->get();

        return view('auth.services.edit', compact('service', 'categories'));
    }

    /**
     * Handle the update of an existing service.
     *
     * @param  ServiceRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateService(ServiceRequest $request, $id)
    {
        // Retrieve the service for the authenticated user
        $service = Service::where('user_id', auth()->id())->findOrFail($id);

        // Get the validated data from the request
        $validatedData = $request->validated();

        // Clean and assign input data
        $service->name = strip_tags($validatedData['name']);
        $service->description = strip_tags($validatedData['description']);
        $service->category_id = $validatedData['category_id'];
        $service->subcategory_id = $validatedData['subcategory_id'];
        $service->price = (float) number_format($validatedData['price'], 2, '.', '');
        $service->contact = strip_tags($validatedData['contact']);
        $service->latitude = $validatedData['latitude'];
        $service->longitude = $validatedData['longitude'];
        $service->city = strip_tags($validatedData['city']);

        // Delete the image if the user checked the delete picture box
        if ($request->delete_picture) {
            Service::deleteServicePicture($service);
        }

        // Store a new image if it exists
        $service->service_picture = Service::handleServicePicture($request, $service);

        // Ensure the authenticated user owns the service
        if (auth()->user()->id != $service->user_id) {
            return redirect()->route('services')->with('error', 'You are not authorized to update this service.');
        }

        // Save the updated service
        if ($service->save()) {
            return redirect()->route('services.show', $service->id)->with('success', 'Service updated successfully!');
        }

        return redirect()->route('services.edit', $service->id)->with('error', 'Failed to update the service.');
    }

    /**
     * Delete a service.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteService($id)
    {
        // Fetch the service to be deleted based on the authenticated user's ID
        $service = Service::where('user_id', auth()->id())->findOrFail($id);
        $user = auth()->user();

        // Check if the logged-in user is the owner of the service
        if (auth()->user()->id != $service->user_id) {
            return redirect()->route('services')->with('error', 'You are not authorized to delete this service.');
        }

        // Delete the service picture if it exists
        Service::deleteServicePicture($service);

        // Delete the service record from the database
        if ($service->delete()) {
            return redirect()->route('users.profile', $user->id)->with('success', 'Service deleted successfully!');
        }

        return redirect()->route('services.show', $service->id)->with('error', 'Failed to delete service.');
    }
}
