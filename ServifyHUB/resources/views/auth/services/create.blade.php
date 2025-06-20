@extends('layouts.default') <!-- Extends the default layout for the page -->

@section('title', 'Create Service') <!-- Sets the title for the create page -->

@section('content')
    <div class="container mt-5">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h3 class="text-center text-orange">Create a New Service</h3> <!-- Page title -->

        <!-- Form for creating a new service -->
        <form action="{{ route('services.create') }}" method="POST" enctype="multipart/form-data">
            @csrf <!-- CSRF token for security -->

            <!-- Input field for the service name -->
            <div class="mb-3">
                <label for="name" class="form-label">Service Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Service name">
                @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>

            <!-- Textarea for the service description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" placeholder="Description">{{ old('description') }}</textarea>
                @if ($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
            </div>

            <!-- Category selection -->
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-control" id="category" name="category_id">
                    <option value="">-- Select a Category --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @if ($errors->has('category_id'))
                    <span class="text-danger">{{ $errors->first('category_id') }}</span>
                @endif
            </div>

            <!-- Subcategory selection -->
            <div class="mb-3">
                <label for="subcategory" class="form-label">Subcategory</label>
                <select class="form-control" id="subcategory" name="subcategory_id" disabled>
                    <option value="">-- Select a Subcategory --</option>
                    @foreach ($categories as $category)
                        @foreach ($category->subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}" data-category="{{ $category->id }}">
                                {{ $subcategory->name }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
                @if ($errors->has('subcategory_id'))
                    <span class="text-danger">{{ $errors->first('subcategory_id') }}</span>
                @endif
            </div>

            <!-- Input field for service price -->
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" placeholder="Price" min="0.01" step="0.01">
                @if ($errors->has('price'))
                    <span class="text-danger">{{ $errors->first('price') }}</span>
                @endif
            </div>

            <!-- Input field for service provider's contact -->
            <div class="mb-3">
                <label for="contact" class="form-label">Contact</label>
                <input type="text" class="form-control" id="contact" name="contact" value="{{ old('contact') }}" placeholder="Contact">
                @if ($errors->has('contact'))
                    <span class="text-danger">{{ $errors->first('contact') }}</span>
                @endif
            </div>

            <!-- File input for service picture (optional) -->
            <div class="mb-3">
                <label for="service_picture" class="form-label">Service Picture (Optional)</label>
                <input type="file" class="form-control" id="service_picture" name="service_picture" accept="image/*">
                @if ($errors->has('service_picture'))
                    <span class="text-danger">{{ $errors->first('service_picture') }}</span>
                @endif
            </div>

            <!-- Location -->
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <div class="embed-responsive embed-responsive-16by9">
                    <div id="map" class="embed-responsive-item" style="height: 400px;"></div>
                </div>

                <!-- Hidden latitude and longitude inputs -->
                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', 46.3604544) }}">
                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', 25.7992867) }}">
                <input type="hidden" id="city" name="city" value="{{ old('city') }}">
            </div>

            <!-- Checkbox for terms and conditions -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="terms" name="terms" {{ old('terms') ? 'checked' : '' }}>
                <label class="form-check-label" for="terms">I accept the terms and conditions</label>
                @if ($errors->has('terms'))
                    <span class="text-danger">{{ $errors->first('terms') }}</span>
                @endif
            </div>

            <!-- Submit button to save the new service -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary mt-3 w-100 p-2">Save Service</button>
            </div>

            <!-- Button to return to the services list -->
            <div class="text-center mt-3">
                <a href="{{ url()->previous() ?: route('services.all') }}" class="btn btn-secondary">Go Back</a>
            </div>
        </form>
    </div>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let categorySelect = document.getElementById("category");
            let subcategorySelect = document.getElementById("subcategory");

            categorySelect.addEventListener("change", function() {
                let selectedCategory = this.value;
                let subcategoryOptions = subcategorySelect.querySelectorAll("option");

                subcategorySelect.disabled = !selectedCategory; // Disable if no category selected
                subcategorySelect.value = ""; // Reset selection

                subcategoryOptions.forEach(option => {
                    if (!option.dataset.category || option.dataset.category === selectedCategory) {
                        option.hidden = false;
                    } else {
                        option.hidden = true;
                    }
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            // Initialize the map
            var map = L.map('map').setView([{{ old('latitude', 46.3604544) }}, {{ old('longitude', 25.7992867) }}], 13);

            // Set up the OpenStreetMap layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Initialize the marker
            var marker = L.marker([{{ old('latitude', 46.3604544) }}, {{ old('longitude', 25.7992867) }}]).addTo(map);

            // Handle click event on the map
            map.on('click', function(e) {
                // Update the marker's position
                marker.setLatLng(e.latlng);

                // Update the hidden input fields for latitude and longitude
                document.getElementById('latitude').value = e.latlng.lat;
                document.getElementById('longitude').value = e.latlng.lng;

                // Reverse geocoding with the Nominatim API
                fetch(`https://nominatim.openstreetmap.org/reverse?lat=${e.latlng.lat}&lon=${e.latlng.lng}&format=json`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Geocoding response:', data); // Log the full response to debug

                        // Default city if nothing is found
                        let city = 'Unknown city';

                        // Check if address data is available in the response
                        if (data.address) {
                            // Check for city, town, or village in the response
                            if (data.address.city) {
                                city = data.address.city;
                            } else if (data.address.town) {
                                city = data.address.town;
                            } else if (data.address.village) {
                                city = data.address.village;
                            } else if (data.address.state) {
                                city = data.address.state;
                            } else if (data.address.country) {
                                city = data.address.country;
                            }
                        }

                        // Update the hidden city input field with the determined city value
                        document.getElementById('city').value = city;
                    })
                    .catch(error => {
                        console.error('Geocoding error:', error);
                        document.getElementById('city').value = 'Unknown city'; // Default to 'Unknown city' if an error occurs
                    });
            });
        });
    </script>
@endsection
