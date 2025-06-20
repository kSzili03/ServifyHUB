@extends("layouts.default") <!-- Extends the default layout for the page -->

@section("title", "Login") <!-- Sets the title for the page -->

@section("content")
    <!-- Main container for the login page with flexbox for centering -->
    <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center" id="template-bg-3">

        <!-- Success message after successful login or registration -->
        @if(session()->has("success"))
            <div class="alert alert-success">
                {{ session()->get("success") }}
            </div>
        @endif

        <!-- Error message after error login-->
        @if(session()->has("error"))
            <div class="alert alert-danger">
                {{ session()->get("error") }}
            </div>
        @endif

        <!-- Card for the login form -->
        <div class="card text-dark bg-light mb-5 p-5 bg-light bg-gradient col-md-4" style="border-radius: 15px">
            <div class="card-header text-center" style="background-color: #f8f9fa;">
                <h3>Login</h3>
            </div>
            <div class="card-body mt-3">
                <!-- Login form -->
                <form name="login" action="{{ route('login.post') }}" method="POST">
                    @csrf <!-- CSRF protection token for security -->

                    <!-- Email input -->
                    <div class="input-group form-group mt-3">
                        <input type="text" class="form-control text-center p-3" placeholder="Email" id="email" name="email">
                    </div>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif

                    <!-- Password input with eye icon -->
                    <div class="input-group form-group mt-3">
                        <div class="position-relative w-100">
                            <!-- Password field -->
                            <input type="password" class="form-control p-3 text-center" placeholder="Password" id="password" name="password">

                            <!-- Eye icon to toggle password visibility -->
                            <span class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6c757d;" id="togglePassword">
                                <i class="fa fa-eye" id="togglePasswordIcon"></i>
                            </span>
                        </div>

                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>

                    <!-- Remember Me checkbox -->
                    <div class="form-group mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>

                    <!-- Submit button -->
                    <div class="text-center">
                        <input type="submit" value="Login" class="btn btn-primary mt-3 w-100 p-2" name="login-btn">
                    </div>
                </form>

                <!-- Link to register page if the user doesn't have an account -->
                <div class="text-center mt-3">
                    <p>Don't have an account? <a href="{{ route('register') }}">Register Here</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for toggling password visibility -->
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const passwordIcon = document.getElementById('togglePasswordIcon');
            const isPasswordHidden = passwordField.type === 'password';
            passwordField.type = isPasswordHidden ? 'text' : 'password';
            passwordIcon.classList.toggle('fa-eye');
            passwordIcon.classList.toggle('fa-eye-slash');
        });
    </script>
@endsection
