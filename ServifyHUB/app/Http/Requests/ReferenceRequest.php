<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReferenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Allow only authenticated users to make a request
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Switch based on the route name to define specific validation rules
        switch ($this->route()->getName()) {
            case 'references.store':
                return $this->storeRules();

            case 'references.update':
                return $this->updateRules();

            default:
                return [];
        }
    }

    /**
     * Validation rules for storing (creating) a reference.
     *
     * @return array
     */
    private function storeRules()
    {
        return [
            'title' => 'nullable|string|max:255',  // Optional title (max 255 characters)
            'description' => 'nullable|string',    // Optional description
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048', // Image is required for creating a reference
        ];
    }

    /**
     * Validation rules for updating a reference.
     *
     * @return array
     */
    private function updateRules()
    {
        return [
            'title' => 'nullable|string|max:255',  // Optional title (max 255 characters)
            'description' => 'nullable|string',    // Optional description
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Image is optional for updating a reference
        ];
    }

    /**
     * Custom validation error messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.max' => 'The title may not be greater than 255 characters.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be of type: jpg, jpeg, png, or gif.',
            'image.max' => 'The image may not be larger than 2MB.',
        ];
    }
}
