<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReferenceRequest;
use App\Models\User;
use App\Models\Reference;
use Illuminate\Support\Facades\Auth;

class ReferenceController extends Controller
{
    /**
     * Store a new reference for the authenticated user.
     *
     * @param  ReferenceRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReferenceRequest $request)
    {
        // Create a new reference
        $reference = new Reference();
        $reference->user_id = Auth::id(); // Associate with the logged-in user
        $reference->title = $request->title ?? '';  // Set title to empty string if not provided
        $reference->description = $request->description ?? ''; // Set description to empty string if not provided

        // Handle image upload if provided
        $reference->image_path = Reference::addReferencePicture($request);

        // Save the reference to the database
        $reference->save();

        // Redirect with success message
        return redirect()->route('users.profile', Auth::id())->with('success', 'Reference added successfully.');
    }

    /**
     * Show references for a specific user.
     *
     * @param  int  $userId
     * @return \Illuminate\View\View
     */
    public function show($userId)
    {
        // Find the user by ID
        $user = User::getUserById($userId);
        // Retrieve all references associated with this user
        $references = $user->references;

        // Return the profile view with user and their references
        return view('users.profile', compact('user', 'references'));
    }

    /**
     * Show the form to edit an existing reference.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Find the reference by ID
        $reference = Reference::getReferenceById($id);

        // Check if the logged-in user is the owner of the reference
        if (auth()->user()->id != $reference->user_id) {
            return redirect()->route('users.profile', auth()->user()->id)->with('error', 'You are not authorized to edit this reference.');
        }

        // Return the edit view with reference data
        return view('auth.references.edit', compact('reference'));
    }

    /**
     * Update an existing reference.
     *
     * @param  ReferenceRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ReferenceRequest $request, $id)
    {
        // Find the reference by ID
        $reference = Reference::getReferenceById($id);

        // Check if the logged-in user is the owner of the reference
        if (auth()->user()->id != $reference->user_id) {
            return redirect()->route('profile.show', auth()->user()->id)->with('error', 'You are not authorized to update this reference.');
        }

        // Update the reference fields with validated data
        $reference->title = $request->title ?? '';  // Set title to empty string if not provided
        $reference->description = $request->description ?? '';  // Set description to empty string if not provided

        // Handle image upload if provided
        $reference->image_path = Reference::handleReferencePicture($request, $reference->image_path);

        // Save the updated reference data
        $reference->save();

        // Redirect with success message
        return redirect()->route('users.profile', Auth::id())->with('success', 'Reference updated successfully.');
    }

    /**
     * Delete a reference.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Find the reference by ID
        $reference = Reference::getReferenceById($id);

        // Check if the logged-in user is the owner of the reference
        if (auth()->user()->id != $reference->user_id) {
            return redirect()->route('users.profile', auth()->user()->id)->with('error', 'You are not authorized to delete this reference.');
        }

        // Delete the associated image if it exists
        Reference::deleteReferencePicture($reference->image_path);

        // Delete the reference from the database
        $reference->delete();

        // Redirect with success message
        return redirect()->route('users.profile', auth()->user()->id)->with('success', 'Reference deleted successfully.');
    }
}
