@extends("layouts.default") <!-- Extends the default layout for the page -->

@section("title", "Edit Profile") <!-- Sets the title of the profile edit page -->

@section("content")
    <div class="container mt-5">
        <div class="card p-5 bg-light text-dark bg-gradient col-md-6 mx-auto" style="border-radius: 15px">
            <!-- Display success message if profile update is successful -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Display error message if any error occurs -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <h3 class="text-center">Edit Profile</h3>

            <!-- Form for updating user's profile -->
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Display current profile picture if available -->
                @if(auth()->user()->profile_picture)
                    <div class="text-center mt-3 mb-4">
                        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile Picture" class="img-fluid" style="max-width: 100px; height: auto;">
                    </div>

                    <!-- Option to delete the current profile picture -->
                    <div class="d-flex justify-content-center align-items-center mb-4">
                        <input type="checkbox" class="form-check-input me-2" id="delete_picture" name="delete_picture" value="1">
                        <label class="form-check-label" for="delete_picture">Delete Current Profile Picture</label>
                    </div>
                @endif

                <!-- Input field for username -->
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username', auth()->user()->name) }}">
                    @if ($errors->has('username'))
                        <span class="text-danger">{{ $errors->first('username') }}</span>
                    @endif
                </div>

                <!-- Input field for email -->
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="text" class="form-control" id="email" name="email" value="{{ old('email', auth()->user()->email) }}">
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <!-- Input field for new password -->
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <!-- Input field for password confirmation -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    @if ($errors->has('password_confirmation'))
                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                </div>

                <!-- Input field for updating the profile picture -->
                <div class="mb-3">
                    <label for="profile_picture" class="form-label">Update Profile Picture</label>
                    <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
                    <small class="form-text text-muted">JPEG, PNG or GIF format picture.</small>
                    @if ($errors->has('profile_picture'))
                        <span class="text-danger">{{ $errors->first('profile_picture') }}</span>
                    @endif
                </div>

                <!-- Submit button to update the profile -->
                <div class="text-center">
                    <input type="submit" value="Update Profile" class="btn btn-primary mt-3 w-100 p-2">
                </div>

                <!-- Button to return to profile view -->
                <div class="text-center mt-3">
                    <a href="{{ route('profile') }}" class="btn btn-secondary">Return To Profile</a>
                </div>
            </form>
        </div>
    </div>
@endsection
