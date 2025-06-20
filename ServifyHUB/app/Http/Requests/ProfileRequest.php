<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Ensure the user is authorized to make requests
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->route()->getName()) {
            // Validation rules for the registration route
            case 'register.post':
                return $this->registerRules();

            // Validation rules for the login route
            case 'login.post':
                return $this->loginRules();

            // Validation rules for the profile update route
            case 'profile.update':
                return $this->updateProfileRules();

            // Return an empty array for other routes
            default:
                return [];
        }
    }

    /**
     * Get the validation rules for registration.
     *
     * @return array
     */
    private function registerRules()
    {
        return [
            // Ensure username is a required string with a max length of 255 characters
            'username' => 'required|string|max:255',

            // Ensure email is a valid email and unique in the users table
            'email' => 'required|email|unique:users,email',

            // Ensure password is required, minimum length of 8 characters, and contains at least one uppercase, one lowercase, one number, and one special character
            'password' => 'required|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*?&]/|confirmed',

            // Ensure the user accepts the terms and conditions
            'terms' => 'accepted',
        ];
    }

    /**
     * Get the validation rules for login.
     *
     * @return array
     */
    private function loginRules()
    {
        return [
            // Ensure email is required and is a valid email
            'email' => 'required|email',

            // Ensure password is required and is a string
            'password' => 'required|string',
        ];
    }

    /**
     * Get the validation rules for profile updates.
     *
     * @return array
     */
    private function updateProfileRules()
    {
        return [
            // Ensure username is a required string with a max length of 255 characters
            'username' => 'required|string|max:255',

            // Ensure email is required, is a valid email, and is unique in the users table except for the current authenticated user
            'email' => 'required|email|unique:users,email,' . auth()->id(),

            // Ensure the profile picture is optional, but if provided, it must be an image with specific types and max size of 2MB
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Ensure password is optional, but if provided, it must meet the specified requirements
            'password' => 'nullable|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*?&]/|confirmed',

            // Ensure the delete picture option is a boolean (true or false)
            'delete_picture' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom validation error messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // Registration specific messages
            'username.required' => 'The username is required.',
            'email.required' => 'The email address is required.',
            'email.unique' => 'This email address is already taken.',
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 8 characters long.',
            'password.confirmed' => 'The password and confirmation do not match.',
            'password.regex' => 'The password must contain: a lowercase letter, an uppercase letter, a number, and a special character (@$!%*?&).',
            'password_confirmation.required' => 'Password confirmation is required.',
            'terms.accepted' => 'You must accept the terms and conditions.',

            // Profile update specific messages
            'profile_picture.image' => 'The profile picture must be an image.',
            'profile_picture.mimes' => 'The profile picture must be a file of type: jpeg, png, jpg, gif.',
            'profile_picture.max' => 'The profile picture may not be greater than 2MB.',
            'delete_picture.boolean' => 'The delete picture option must be true or false.',
        ];
    }
}
