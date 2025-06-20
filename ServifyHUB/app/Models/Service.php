<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Service extends Model
{
    use HasFactory;

    // Fillable fields including views count and image path
    protected $fillable = [
        'name', 'description', 'price', 'contact', 'service_picture', 'views', 'latitude', 'longitude', 'city', 'user_id', 'category_id', 'sub_category_id',
    ];

    /**
     * Relationship with Category
     *
     * Each service belongs to one category
     */
    public function category()
    {
        return $this->belongsTo(Category::class); // Each service belongs to one category
    }

    /**
     * Relationship with Subcategory
     *
     * Each service belongs to one subcategory
     */
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class); // Each service belongs to one subcategory
    }

    /**
     * Relationship with the user (which user created the service)
     *
     * A service belongs to one user
     */
    public function user()
    {
        return $this->belongsTo(User::class); // A service belongs to one user
    }

    /**
     * Relationship with comments
     *
     * A service can have many comments
     */
    public function comments()
    {
        return $this->hasMany(Comment::class); // A service can have many comments
    }

    /**
     * Get service by ID
     *
     * @param int $serviceId
     * @return \App\Models\Service
     */
    public static function getServiceById($serviceId)
    {
        return self::findOrFail($serviceId); // Find the service by ID or fail if not found
    }

    /**
     * Get the latest services
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getLatestServices($limit)
    {
        return self::latest()->take($limit)->get();
    }

    /**
     * Get the top viewed services
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTopViewedServices($limit)
    {
        return self::orderBy('views', 'desc')->take($limit)->get();
    }

    /**
     * Method to get unique locations from services
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getDistinctLocations()
    {
        return Service::distinct()->pluck('city'); // Get unique cities/locations where services are located
    }

    /**
     * Add a service picture to the storage
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public static function addServicePicture($request)
    {
        if ($request->hasFile('service_picture')) {
            $imagePath = $request->file('service_picture')->store('service_pictures', 'public');
            return $imagePath;
        }

        return null;
    }

    /**
     * Handle the service picture upload and deletion
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Service $service
     * @return string|null
     */
    public static function handleServicePicture($request, $service)
    {
        if ($request->hasFile('service_picture')) {
            if ($service->service_picture) {
                Storage::disk('public')->delete($service->service_picture);
            }
            $imagePath = $request->file('service_picture')->store('service_pictures', 'public');
            return $imagePath;
        }
        return $service->service_picture;
    }

    /**
     * Delete a service picture from the storage
     *
     * @param \App\Models\Service $service
     * @return void
     */
    public static function deleteServicePicture($service)
    {
        if ($service->service_picture) {
            Storage::disk('public')->delete($service->service_picture);
            $service->service_picture = null;
        }
    }

    /**
     * Filter services based on request parameters
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function filterServices(Builder $query, $request)
    {
        // Apply search filter
        self::searchFilter($query, $request);

        // Apply sorting filter
        self::sortFilter($query, $request);

        // Apply category filter
        self::categoryFilter($query, $request);

        // Apply subcategory filter
        self::subCategoryFilter($query, $request);

        // Apply price range filter
        self::priceRangeFilter($query, $request);

        // Apply location filter
        self::locationFilter($query, $request);

        return $query;
    }

    /**
     * Apply search filter
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public static function searchFilter(Builder $query, $request)
    {
        if ($request->filled('search')) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                self::searchByName($q, $searchTerm);
                self::searchByDescription($q, $searchTerm);
                self::searchByUser($q, $searchTerm);
                self::searchByCategory($q, $searchTerm);
                self::searchByCity($q, $searchTerm);
            });
        }
    }

    /**
     * Apply sorting filter
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public static function sortFilter(Builder $query, $request)
    {
        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'views_desc':
                    $query->orderBy('views', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
            }
        }
    }

    /**
     * Apply category filter
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public static function categoryFilter(Builder $query, $request)
    {
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
    }

    /**
     * Apply subcategory filter
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public static function subCategoryFilter(Builder $query, $request)
    {
        if ($request->filled('subcategory')) {
            $query->where('subcategory_id', $request->subcategory);
        }
    }

    /**
     * Apply price range filter
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public static function priceRangeFilter(Builder $query, $request)
    {
        if ($request->filled('price_range')) {
            $priceRanges = $request->price_range;
            $query->where(function ($q) use ($priceRanges) {
                foreach ($priceRanges as $range) {
                    list($min, $max) = explode('-', $range);
                    $q->orWhereBetween('price', [(int)$min, (int)$max]);
                }
            });
        }
    }

    /**
     * Apply location filter
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public static function locationFilter(Builder $query, $request)
    {
        if ($request->filled('locations')) {
            $query->whereIn('city', $request->locations);
        }
    }

    /**
     * Apply name search filter
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $searchTerm
     * @return void
     */
    private static function searchByName(Builder $query, $searchTerm)
    {
        $query->orWhere('name', 'like', '%' . $searchTerm . '%');
    }

    /**
     * Apply description search filter
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $searchTerm
     * @return void
     */
    private static function searchByDescription(Builder $query, $searchTerm)
    {
        $query->orWhere('description', 'like', '%' . $searchTerm . '%');
    }

    /**
     * Apply user name search filter
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $searchTerm
     * @return void
     */
    private static function searchByUser(Builder $query, $searchTerm)
    {
        $query->orWhereHas('user', function ($q) use ($searchTerm) {
            $q->where('name', 'like', '%' . $searchTerm . '%');
        });
    }

    /**
     * Apply category search filter
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $searchTerm
     * @return void
     */
    private static function searchByCategory(Builder $query, $searchTerm)
    {
        $query->orWhereHas('category', function ($q) use ($searchTerm) {
            $q->where('name', 'like', '%' . $searchTerm . '%');
        });
    }

    /**
     * Apply city search filter
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $searchTerm
     * @return void
     */
    private static function searchByCity(Builder $query, $searchTerm)
    {
        $query->orWhere('city', 'like', '%' . $searchTerm . '%');
    }

}
