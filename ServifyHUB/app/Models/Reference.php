<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Reference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'image_path',
    ];

    // Relationship with the user
    public function user()
    {
        return $this->belongsTo(User::class); // A reference belongs to the user
    }

    /**
     * Find a reference by ID or fail.
     *
     * @param int $id
     * @return Reference
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function getReferenceById($id)
    {
        return self::findOrFail($id);
    }

    /**
     * Handle image upload when creating a new reference
     *
     * @param $request
     * @return string|null
     */
    public static function addReferencePicture($request)
    {
        if ($request->hasFile('image')) {
            // Store the uploaded image and return its path
            return $request->file('image')->store('user_references', 'public');
        }

        return null;
    }

    /**
     * Handle image update when editing an existing reference
     *
     * @param $request
     * @param $oldImagePath
     * @return string|null
     */
    public static function handleReferencePicture($request, $oldImagePath = null)
    {
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($oldImagePath) {
                Storage::disk('public')->delete($oldImagePath);
            }

            // Store the new image and return its path
            return $request->file('image')->store('user_references', 'public');
        }

        // If no new image is uploaded, return the old image path
        return $oldImagePath;
    }

    /**
     * Handle image deletion when deleting a reference
     *
     * @param $imagePath
     */
    public static function deleteReferencePicture($imagePath)
    {
        if ($imagePath) {
            // Delete the image from storage
            Storage::disk('public')->delete($imagePath);
        }
    }

}
