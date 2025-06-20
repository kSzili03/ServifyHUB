<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    // Fillable attributes for mass assignment
    protected $fillable = ['name', 'category_id'];

    /**
     * Relationship with Category
     *
     * Each subcategory belongs to one category
     */
    public function category()
    {
        return $this->belongsTo(Category::class); // Define the inverse of the one-to-many relationship
    }

    /**
     * Relationship with Services
     *
     * A subcategory can have many services
     */
    public function services()
    {
        return $this->hasMany(Service::class); // A subcategory can have many services
    }
}
