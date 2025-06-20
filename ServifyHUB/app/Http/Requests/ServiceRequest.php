<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check(); // Only authenticated users can make this request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->route()->getName()) {
            case 'services.create.post':
                return $this->createServiceRules(); // Rules for creating a service
            case 'services.update':
                return $this->updateServiceRules(); // Rules for updating a service
            default:
                return [];
        }
    }

    /**
     * Validation rules for creating a service.
     *
     * @return array
     */
    private function createServiceRules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'price' => 'required|numeric|min:0.01',
            'contact' => 'required|string|max:255',
            'terms' => 'accepted',
            'service_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'city' => 'nullable|string|max:255',
        ];
    }

    /**
     * Validation rules for updating a service.
     *
     * @return array
     */
    private function updateServiceRules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'price' => 'required|numeric|min:0.01',
            'contact' => 'required|string|max:255',
            'service_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'delete_picture' => 'nullable|boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'city' => 'nullable|string|max:255',
        ];
    }

    /**
     * Custom messages for validation failures.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // Messages for creating a service
            'name.required' => 'The service name is required.',
            'name.max' => 'The service name may not be greater than 255 characters.',
            'description.required' => 'A description is required.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'subcategory_id.required' => 'Please select a subcategory.',
            'subcategory_id.exists' => 'The selected subcategory is invalid.',
            'price.required' => 'Please provide a price for the service.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price must be at least 0.01.',
            'contact.required' => 'A contact method is required.',
            'contact.max' => 'The contact information may not exceed 255 characters.',
            'terms.accepted' => 'You must accept the terms and conditions.',
            'service_picture.image' => 'The uploaded file must be an image.',
            'service_picture.mimes' => 'The image must be of type: jpeg, png, jpg, gif, or svg.',
            'service_picture.max' => 'The image may not be larger than 2MB.',
            'latitude.numeric' => 'Latitude must be a valid number.',
            'longitude.numeric' => 'Longitude must be a valid number.',
            'city.max' => 'City name may not be longer than 255 characters.',

            // Messages for updating a service
            'delete_picture.boolean' => 'The delete picture option must be true or false.',
        ];
    }
}
