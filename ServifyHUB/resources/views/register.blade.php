@extends("layouts.default") <!-- Extends the default layout for the page -->

@section("title", "Register") <!-- Sets the title for the register page -->

@section("content")
    <!-- Main container for the registration page with flexbox for centering -->
    <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center" id="template-bg-3">

        <!-- Error message display -->
        @if(session()->has("error"))
            <div class="alert alert-danger">
                {{ session()->get("error") }}
            </div>
        @endif

        <!-- Registration form card -->
        <div class="card text-dark bg-light mb-5 p-5 bg-light bg-gradient col-md-4" style="border-radius: 15px">
            <div class="card-header text-center" style="background-color: #f8f9fa;">
                <h3>Register</h3>
            </div>
            <div class="card-body mt-3">
                <form name="register" action="{{ route('register.post') }}" method="POST" id="registerForm">
                    @csrf <!-- CSRF token for security -->

                    <!-- Username input -->
                    <div class="input-group form-group mt-3">
                        <input type="text" class="form-control text-center p-3" placeholder="Username" id="username" name="username" value="{{ old('username') }}">
                    </div>

                    @if ($errors->has('username'))
                        <div class="error-message text-danger mt-1">{{ $errors->first('username') }}</div>
                    @endif

                    <!-- Email input -->
                    <div class="input-group form-group mt-3">
                        <input type="text" class="form-control text-center p-3" placeholder="Email" id="email" name="email" value="{{ old('email') }}">
                    </div>

                    @if ($errors->has('email'))
                        <div class="error-message text-danger mt-1">{{ $errors->first('email') }}</div>
                    @endif

                    <!-- Password input with toggle visibility -->
                    <div class="input-group form-group mt-3 position-relative">
                        <input type="password" class="form-control text-center p-3" placeholder="Password" id="password" name="password">
                        <span class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6c757d;" id="togglePassword">
                            <i class="fa fa-eye" id="togglePasswordIcon"></i>
                        </span>
                    </div>

                    @if ($errors->has('password'))
                        <div class="error-message text-danger mt-1">{{ $errors->first('password') }}</div>
                    @endif

                    <!-- Confirm Password input with toggle visibility -->
                    <div class="input-group form-group mt-3 position-relative">
                        <input type="password" class="form-control text-center p-3" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation">
                        <span class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6c757d;" id="toggleConfirmPassword">
                            <i class="fa fa-eye" id="toggleConfirmPasswordIcon"></i>
                        </span>
                    </div>

                    @if ($errors->has('password_confirmation'))
                        <div class="error-message text-danger mt-1">{{ $errors->first('password_confirmation') }}</div>
                    @endif

                    <!-- Terms and conditions checkbox -->
                    <div class="form-check mt-3">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" {{ old('terms') ? 'checked' : '' }}>
                        <label class="form-check-label" for="terms">I accept the terms and conditions</label>
                    </div>

                    @if ($errors->has('terms'))
                        <div class="error-message text-danger mt-1">{{ $errors->first('terms') }}</div>
                    @endif

                    <!-- Submit button -->
                    <div class="text-center">
                        <input type="submit" value="Register" class="btn btn-primary mt-3 w-100 p-2" name="register-btn">
                    </div>
                </form>

                <!-- Link to login page -->
                <div class="text-center mt-3">
                    <p>Already have an account? <a href="{{ route('login') }}">Login Here</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for toggling password visibility -->
    <script>
        // Toggle password visibility for the password field
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const passwordIcon = document.getElementById('togglePasswordIcon');
            const isPasswordHidden = passwordField.type === 'password';
            passwordField.type = isPasswordHidden ? 'text' : 'password';
            passwordIcon.classList.toggle('fa-eye');
            passwordIcon.classList.toggle('fa-eye-slash');
        });

        // Toggle password visibility for the confirm password field
        document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
            const confirmPasswordField = document.getElementById('password_confirmation');
            const confirmPasswordIcon = document.getElementById('toggleConfirmPasswordIcon');
            const isConfirmPasswordHidden = confirmPasswordField.type === 'password';
            confirmPasswordField.type = isConfirmPasswordHidden ? 'text' : 'password';
            confirmPasswordIcon.classList.toggle('fa-eye');
            confirmPasswordIcon.classList.toggle('fa-eye-slash');
        });
    </script>
@endsection
