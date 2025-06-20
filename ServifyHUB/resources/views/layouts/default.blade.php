<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - ServifyHub</title>

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Add scrolling to the notifications dropdown */
        .dropdown-menu {
            max-height: 300px;
            overflow-y: auto;
        }

        /* Ensure long notification text doesn't overflow and wraps properly */
        .dropdown-menu .dropdown-item {
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
        }

        /* Add a subtle border between the last notification and buttons */
        .dropdown-menu li.notification:last-child {
            border-bottom: 1px solid #ddd;
        }

        /* Style for buttons in the dropdown */
        .dropdown-menu form button {
            padding: 8px 12px;
            font-weight: bold;
            display: block;
            width: 100%;
        }

        /* Add a little padding for better spacing */
        .dropdown-menu .btn-link {
            padding: 8px 12px;
        }

        /* Make "Delete All" button text smaller */
        .dropdown-menu .btn-link.delete-all {
            font-size: 0.85rem;
        }

        /* Custom styling for responsive icons */
        .nav-item .fa-message, .nav-item .fa-user-circle, .nav-item .fa-sign-out-alt, .nav-item .fa-bell {
            font-size: 1.4rem; /* Consistent size for all navbar icons */
        }

        /* Align profile text and icon nicely */
        .nav-item .profile-text {
            font-size: 1.1rem;
            margin-right: 8px;
            display: inline-block;
        }

        /* Make Profile text and icon clickable */
        .nav-item .profile-text, .nav-item .profile-icon {
            display: inline-block;
            cursor: pointer;
        }

        /* Style the profile dropdown */
        .dropdown-toggle::after {
            content: "\f107"; /* FontAwesome caret icon */
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            margin-left: 5px;
        }

        /* Add padding to navbar items for spacing */
        .nav-item {
            padding: 8px 10px;
        }

        /* Fix spacing around the profile dropdown */
        .dropdown-menu {
            min-width: 160px;
        }
    </style>
</head>
<body style="padding-top: 70px;">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <svg height="40" width="200" xmlns="http://www.w3.org/2000/svg">
                <text x="5" y="30" fill="white" font-size="22" letter-spacing="5" font-weight="600">SERVIFYHUB</text>
            </svg>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('services.all') }}">
                        <i class="fas fa-tools me-2"></i> Services
                    </a>
                </li>

                @guest
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-2"></i> Register
                        </a>
                    </li>
                @endguest

                @auth
                    <!-- Notifications Dropdown -->
                    <li class="nav-item notification-item dropdown">
                        <a class="nav-link notification-icon d-flex align-items-center" href="#" id="navbarDropdownNotifications" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- Bell Icon -->
                            <i class="fas fa-bell me-2"></i>
                            <span>Notifications</span>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <!-- Badge with unread count, positioned to the right of the text -->
                                <span class="badge bg-danger rounded-circle ms-2" style="font-size: 0.8rem; padding: 0.3rem 0.6rem;">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                            @foreach(auth()->user()->notifications as $notification)
                                <li class="notification {{ $notification->read_at ? 'read' : 'unread' }} border-bottom"
                                    style="{{ $notification->read_at ? '' : 'background-color: #e9ecef; border-left: 4px solid #007bff; color: #495057;' }}">
                                    @php
                                        // Check if the data is in JSON format and decode it
                                        $data = json_decode($notification->data, true); // true converts the JSON into an associative array
                                        // Now you can access the message property safely
                                        $message = $data['message'] ?? 'No message available'; // Use a fallback message if not set
                                    @endphp
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('notifications.markAsRead', $notification->id) }}">
                                        <i class="fas fa-info-circle me-2"></i>{{ $message }}
                                    </a>
                                </li>
                            @endforeach

                            <!-- "Mark all as read" button -->
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <li class="text-center" style="padding: 0.25rem 0;">
                                    <form action="{{ route('notifications.markAllAsRead') }}" method="GET">
                                        <button type="submit" class="btn btn-link text-primary fw-bold" style="font-size: 0.85rem; padding: 0.3rem 0;">
                                            <i class="fas fa-check me-1"></i> Mark All as Read
                                        </button>
                                    </form>
                                </li>
                            @endif

                            <!-- Fine separator line between buttons -->
                            <li><hr class="dropdown-divider" style="margin: 0.25rem 0;"></li>

                            <!-- "Delete all notifications" button -->
                            <li class="text-center" style="padding: 0.25rem 0;">
                                <form action="{{ route('notifications.deleteAll') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-danger fw-bold" style="font-size: 0.85rem; padding: 0.3rem 0;">
                                        <i class="fas fa-trash-alt me-1"></i> Delete All
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center dropdown-toggle" href="#" id="profileDropdown"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i> Profile
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                            <!-- Profile link -->
                            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('profile') }}">
                                    <i class="fas fa-user-circle me-2"></i> Profile
                                </a></li>
                            <!-- Messages link -->
                            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('messages.index') }}">
                                    <i class="fas fa-envelope me-2"></i> Messages
                                </a></li>
                            <!-- Logout link -->
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Main content -->
<div class="container mt-4">
    @yield('content')
</div>

<!-- Footer -->
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container text-center">
        <p class="mb-0">&copy; {{ date('Y') }} ServifyHub - Szilárd Kovács. All rights reserved.</p>
        <div>
            <a href="#" class="text-white text-decoration-none">Privacy Policy</a> |
            <a href="#" class="text-white text-decoration-none">Terms of Service</a>
        </div>
    </div>
</footer>

<!-- Bootstrap 5.3 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
