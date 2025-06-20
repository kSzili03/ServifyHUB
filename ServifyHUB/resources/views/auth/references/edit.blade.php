@extends('layouts.default') <!-- Extends the default layout for the page -->

@section('title', 'Edit Reference') <!-- Sets the title for the edit page -->

@section('content')
    <div class="container mt-5">
        <!-- Card for editing a reference -->
        <div class="card p-5 bg-light text-dark bg-gradient col-md-6 mx-auto" style="border-radius: 15px">

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <h3 class="text-center">Edit Reference</h3> <!-- Title of the form -->

            <!-- Form to update the reference -->
            <form action="{{ route('references.update', $reference->id) }}" method="POST" enctype="multipart/form-data">
                @csrf <!-- CSRF token for security -->
                @method('PUT') <!-- Specifies the PUT method for form submission -->

                <!-- Input field for reference title -->
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $reference->title) }}">
                    @if ($errors->has('title'))
                        <span class="text-danger">{{ $errors->first('title') }}</span>
                    @endif
                </div>

                <!-- Textarea for reference description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description">{{ old('description', $reference->description) }}</textarea>
                    @if ($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('title') }}</span>
                    @endif
                </div>

                <!-- File input for reference image -->
                <div class="mb-3">
                    <label for="image" class="form-label">Reference Image (Optional)</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    @if ($errors->has('image'))
                        <span class="text-danger">{{ $errors->first('image') }}</span>
                    @endif
                </div>

                <!-- Display current image if available -->
                @if($reference->image_path)
                    <div class="mb-3">
                        <label for="current_image" class="form-label">Current Image</label>
                        <div class="text-center">
                            <!-- Display current image -->
                            <img src="{{ asset('storage/' . $reference->image_path) }}" alt="Reference Image" class="img-fluid" style="max-width: 100%; height: auto; border-radius: 15px;">
                        </div>
                    </div>
                @endif

                <!-- Submit button to update the reference -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary mt-3 w-100 p-2">Update Reference</button>
                </div>

                <!-- Button to return to the previous page -->
                <div class="text-center mt-3">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Go Back</a>
                </div>
            </form>
        </div>
    </div>
@endsection
