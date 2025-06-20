@extends("layouts.default") <!-- Extends the default layout for the page -->

@section("title", "All Services") <!-- Sets the title for the services list page -->

@section("content")
    <div class="container mt-5">
        <h3 class="text-center mb-4">All Services</h3>

        <!-- Filter and search form -->
        <form method="GET" action="{{ route('services.all') }}" class="mb-4">
            <div class="row">
                <!-- Search field (Top left) -->
                <div class="col-12 col-md-9 mb-3">
                    <input type="text" name="search" class="form-control text-center" placeholder="Search" value="{{ request('search') }}">
                </div>

                <!-- Sorting options (Top right) -->
                <div class="col-12 col-md-3 mb-3">
                    <select name="sort_by" class="form-select text-center" onchange="this.form.submit()">
                        <option value="">Sort by</option>
                        <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="views_desc" {{ request('sort_by') == 'views_desc' ? 'selected' : '' }}>Most Viewed</option>
                        <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Alphabetical A-Z</option>
                        <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Alphabetical Z-A</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <!-- Price and Category filters (Left side) -->
                <div class="col-12 col-md-3 mb-3">
                    <!-- Category filter -->
                    <div class="form-group mb-3">
                        <select name="category" class="form-select text-center" id="category-select">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subcategory filter -->
                    <div class="form-group mb-3">
                        <select name="subcategory" class="form-select text-center" id="subcategory-select" {{ request('category') ? '' : 'disabled' }}>
                            <option value="">-- Select Subcategory --</option>
                            @foreach($categories as $category)
                                @foreach($category->subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" data-category="{{ $category->id }}"
                                        {{ request('subcategory') == $subcategory->id ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <!-- Price range filter (Card styled) -->
                    <div class="form-group mt-3">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="mb-3">Price Range</h6>
                                @foreach([0 => 50, 50 => 100, 100 => 200, 200 => 500, 500 => 1000] as $min => $max)
                                    <div class="form-check form-check-lg">
                                        <!-- Custom checkbox with Bootstrap classes -->
                                        <input type="checkbox" name="price_range[]" value="{{ $min }}-{{ $max }}" class="form-check-input invisible-checkbox"
                                            {{ in_array("$min-$max", (array)request('price_range', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label price-range-label" style="font-size: 1rem;">
                                            {{ $min }} - {{ $max }} USD
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Location filter (Card styled) -->
                    <div class="form-group mt-3">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="mb-3">Locations</h6>
                                @foreach($locations as $location)
                                    <div class="form-check form-check-lg">
                                        <!-- Custom checkbox with Bootstrap classes -->
                                        <input type="checkbox" name="locations[]" value="{{ $location }}" class="form-check-input invisible-checkbox"
                                            {{ in_array($location, (array)request('locations', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label location-label" style="font-size: 1rem;">
                                            {{ $location }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Apply filters button -->
                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </div>

                    @if(auth()->check())
                        <!-- New Service button -->
                        <div class="form-group mt-3">
                            <a href="{{ route('services.create') }}" class="btn btn-primary w-100" style="margin-bottom: 15px">
                                <i class="bi bi-plus-circle"></i> New Service
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Service listing (Right side) -->
                <div class="col-12 col-md-9">
                    <div class="row">
                        @if($services->isEmpty())
                            <p class="text-center">No services available with the selected filters.</p>
                        @else
                            @foreach($services as $service)
                                <div class="col-12 mb-4">
                                    <div class="card service-card text-dark bg-light h-100">
                                        <div class="row g-0">
                                            <!-- Service image (Left side) -->
                                            <div class="col-12 col-md-4">
                                                <a href="{{ route('services.show', $service->id) }}">
                                                    <img src="{{ $service->service_picture ? Storage::url($service->service_picture) : asset('storage/placeholder.svg') }}"
                                                         class="img-fluid w-100 h-100"
                                                         style="object-fit: cover; max-height: 200px;"
                                                         alt="{{ $service->name }}">
                                                </a>
                                            </div>

                                            <!-- Service description and price (Right side) -->
                                            <div class="col-12 col-md-8">
                                                <div class="card-body d-flex flex-column justify-content-center" style="height: 100%;">

                                                    <!-- Service name -->
                                                    <h5 class="card-title text-center text-md-start">
                                                        <a href="{{ route('services.show', $service->id) }}" class="service-card-text" style="text-decoration: none;">
                                                            {{ $service->name }}
                                                        </a>
                                                    </h5>

                                                    <!-- Category and subcategory -->
                                                    <p class="text-muted mb-2 text-center text-md-start">
                                                        <strong>
                                                            {{ $service->category->name }}
                                                            @if($service->subcategory)
                                                                - {{ $service->subcategory->name }}
                                                            @endif
                                                        </strong>
                                                    </p>

                                                    <p class="text-muted mb-2 text-center text-md-start">
                                                        <strong>Location: {{ $service->city }}</strong>
                                                    </p>

                                                    <!-- Price -->
                                                    <div class="d-flex justify-content-center justify-content-md-end align-items-center mt-3">
                                                        <p class="mb-0"><strong>${{ number_format($service->price, 2) }}</strong></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <nav aria-label="Service pagination">
                        <ul class="pagination pagination-lg justify-content-center flex-wrap">
                            <!-- Previous Page Link -->
                            <li class="page-item {{ $services->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $services->previousPageUrl() }}" tabindex="-1">Previous</a>
                            </li>

                            <!-- Page Number Links -->
                            @foreach ($services->getUrlRange(1, $services->lastPage()) as $page => $url)
                                <li class="page-item {{ $services->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            <!-- Next Page Link -->
                            <li class="page-item {{ $services->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $services->nextPageUrl() }}">Next</a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let categorySelect = document.getElementById("category-select");
            let subcategorySelect = document.getElementById("subcategory-select");

            function updateSubcategories() {
                let selectedCategory = categorySelect.value;
                let subcategoryOptions = subcategorySelect.querySelectorAll("option");

                subcategorySelect.value = "";
                subcategorySelect.disabled = !selectedCategory; // Disable subcategory if no category is selected

                subcategoryOptions.forEach(option => {
                    if (!option.value) return; // Don't affect the first option
                    option.hidden = option.getAttribute("data-category") !== selectedCategory;
                });
            }

            categorySelect.addEventListener("change", updateSubcategories);
            updateSubcategories(); // Initialize on page load
        });
    </script>
@endsection
