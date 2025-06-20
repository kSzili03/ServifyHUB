<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;

class UserController extends Controller
{
    /**
     * Show the profile of a specific user.
     *
     * @param  User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        // Fetch the services and reviews associated with the user
        $services = $user->services();
        $reviews = $user->reviews();

        // Return the view with user details, services, and reviews
        return view('users.profile', [
            'user' => $user,
            'services' => $services,
            'reviews' => $reviews
        ]);
    }

    /**
     * Handle the login process and validation.
     *
     * @param  ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginPost(ProfileRequest $request)
    {
        // ProfileRequest handles validation for login
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // Attempt to authenticate the user
        if (Auth::attempt($credentials, $remember)) {
            return redirect()->intended(route("home"));
        }

        // If authentication fails, redirect back to login
        return redirect(route("login"))->with("error", "Invalid email or password");
    }

    /**
     * Handle the registration process and validation.
     *
     * @param  ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerPost(ProfileRequest $request)
    {
        // ProfileRequest handles validation for registration
        $user = new User();
        $user->name = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        // Save the user after successful validation
        if ($user->save()) {
            return redirect(route("login"))->with("success", "User created successfully");
        }

        return redirect(route("register"))->with("error", "Failed to create account");
    }

    /**
     * Show the authenticated user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function showProfile()
    {
        // Fetch the authenticated user
        $user = auth()->user();
        $services = $user->services;
        $references = $user->references;

        // Mark all reviews as read
        Notification::markReviewsRead($user->id);

        // Return the profile view with user details, services, and references
        return view('users.profile', compact('user', 'services', 'references'));
    }

    /**
     * Show the profile edit form for the authenticated user.
     *
     * @return \Illuminate\View\View
     */
    public function editProfile()
    {
        return view('auth.profile.edit');
    }

    /**
     * Update the authenticated user's profile with new information.
     *
     * @param  ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(ProfileRequest $request)
    {
        // Fetch the authenticated user
        $user = auth()->user();
        $user->name = $request->username;
        $user->email = $request->email;

        // If the user wants to delete the profile picture
        if ($request->has('delete_picture') && $request->delete_picture) {
            User::deleteProfilePicture($user);
        }

        // If the user has uploaded a new profile picture, handle the upload
        if ($request->hasFile('profile_picture')) {
            $user->profile_picture = User::handleProfilePicture($request, $user);
        }

        // If the user has provided a new password, hash and update it
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Save the updated profile
        if ($user->save()) {
            return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
        }

        return redirect()->route('profile.edit')->with('error', 'Failed to update profile.');
    }

    /**
     * Log the user out of the application.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Log out the authenticated user
        Auth::logout();

        // Invalidate the session and regenerate the CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect the user to the home page
        return redirect()->route('home');
    }
}
