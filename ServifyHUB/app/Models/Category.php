<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    // The fillable fields for the category
    protected $fillable = ['name'];

    // Relationship to services
    public function services()
    {
        return $this->hasMany(Service::class); // A category can have many services
    }

    // Relationship to subcategories
    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class); // A category can have many subcategories
    }

    /**
     * Get all categories with their subcategories.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCategoriesWithSubcategories()
    {
        return self::with('subcategories')->get(); // Get all categories with their subcategories
    }
}
