@extends("layouts.default") <!-- Extends the default layout for the page -->

@section("title", $service->name) <!-- Sets the title for the home page -->

@section("content")
    <div class="container mt-5">
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-4">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Profile Card -->
                <div class="card mb-4 shadow-lg" style="border-radius: 15px; background-color: #f8f9fa;">
                    <div class="text-center p-4">
                        <!-- Profile Picture -->
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="profilePicture" class="rounded-circle" width="150" height="150" style="object-fit: cover;">
                        @else
                            <img src="{{ asset('storage/avatar.svg') }}" alt="profilePicture" class="rounded-circle" width="150" height="150">
                        @endif
                    </div>
                    <div class="card-body">
                        <!-- User Information -->
                        <h4 class="text-center">
                            <a href="{{ auth()->check() && auth()->user()->id == $user->id ? route('profile') : route('users.profile', $user->id) }}" class="text-decoration-none">
                                {{ $user->name }}
                            </a>
                        </h4>
                        <p class="text-center">Email: {{ $user->email }}</p>

                        <!-- User Rating Section -->
                        <div class="text-center mt-4" style="border-top: 2px solid #9E9E9E; padding-top: 15px;">
                            <h5>Average Rating</h5>
                            <p>
                                @if($user->average_rating)
                                    <span>{{ number_format($user->average_rating, 1) }}</span>
                                    <span>
                                        @for ($i = 0; $i < 5; $i++)
                                            <i class="fa{{ $i < $user->average_rating ? 's' : 'r' }} fa-star"></i>
                                        @endfor
                                    </span>
                                @else
                                    <span>No ratings yet</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="card mb-4 shadow-lg" style="border-radius: 15px; background-color: #f8f9fa;">
                    <div class="card-body">
                        <!-- Displaying Contact Form if Logged In -->
                        @if(auth()->check() && auth()->user()->id != $user->id)
                            <h5 class="text-center">Contact {{ $user->name }}</h5>
                            <form action="{{ route('messages.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                                <div class="form-group">
                                    <label for="subject">Subject</label>
                                    <input type="text" name="subject" class="form-control" id="subject" placeholder="Subject">
                                    @if ($errors->has('subject'))
                                        <span class="text-danger">{{ $errors->first('subject') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea name="message" class="form-control" id="message" rows="4" placeholder="Write your message here..."></textarea>
                                    @if ($errors->has('message'))
                                        <span class="text-danger">{{ $errors->first('message') }}</span>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-custom w-100 mt-3">Send Message</button>
                            </form>
                            <!-- Message Display for Same User -->
                        @elseif(auth()->check() && auth()->user()->id == $user->id)
                            <p class="text-center">You cannot send a message to yourself.</p>
                            <!-- Message Display for Non-Logged-in User -->
                        @else
                            <h5 class="text-center">Contact {{ $user->name }}</h5>
                            <p class="text-danger text-center">You must be logged in to send a message.</p>
                        @endif
                    </div>
                </div>

                <!-- Map Section -->
                <div class="card mb-4 shadow-lg" style="border-radius: 15px; background-color: #f8f9fa;">
                    <div class="card-body">
                        <h5 class="text-center">Service Location Map</h5>
                        <!-- Replace the iframe with a div for the Leaflet map -->
                        <div id="service-map" style="height: 400px;"></div>
                    </div>
                </div>

            </div>

            <!-- Right Column -->
            <div class="col-md-8">
                <!-- Profile Info Card -->
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 20px; background-color: #ffffff;">
                    <div class="card-body p-4">
                        <!-- Service Image Section -->
                        @if($service->service_picture)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $service->service_picture) }}" class="img-fluid rounded-3 w-100" style="max-height: 350px; object-fit: cover;" alt="Service Image">
                            </div>
                        @else
                            <div class="mb-4">
                                <img src="{{ asset('storage/placeholder.svg') }}" class="img-fluid rounded-3 w-100" style="max-height: 350px; object-fit: cover;" alt="Service Image">
                            </div>
                        @endif

                        <!-- Service Name and Price -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-dark fw-bold" style="word-wrap: break-word; max-width: 80%;">{{ $service->name }}</h4>
                            <p class="fw-bold text-success mb-0" style="font-size: 1.5rem; line-height: 1.4;">${{ number_format($service->price, 2) }}</p>
                        </div>

                        <hr class="my-4">

                        <!-- Service Description -->
                        <div class="mb-3">
                            <p class="fs-5 fw-normal text-dark" style="word-wrap: break-word; overflow-wrap: break-word; max-width: 100%; font-size: 1.2rem;">{{ $service->description }}</p>
                        </div>

                        <hr class="my-4">

                        <!-- Contact Section (Font size adjusted to match description) -->
                        <div class="d-flex justify-content-between mb-3">
                            <p class="text-muted mb-0" style="font-size: 1.2rem; width: 100%; word-wrap: break-word; overflow-wrap: break-word;"><strong>Contact:</strong> {{ $service->contact }}</p>
                        </div>

                        @auth
                            @if($service->user_id === auth()->user()->id)
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-outline-secondary btn-sm conversation-link me-2">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('services.delete', $service->id) }}" method="POST" style="display:inline;" id="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="delete-btn">
                                            <i class="fas fa-trash-alt me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="card mb-4 shadow-lg" style="border-radius: 15px; background-color: #f8f9fa;">
                    <div class="card-body">
                        <h4>Comments</h4>
                        @foreach($service->comments as $comment)
                            <div class="card my-3" id="comment-{{ $comment->id }}">
                                <div class="card-body position-relative">
                                    <div class="d-flex align-items-start comment-container">
                                        @if($comment->user->profile_picture)
                                            <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" alt="profilePicture" class="rounded-circle" width="50" height="50" style="object-fit: cover; margin-right: 15px;">
                                        @else
                                            <img src="{{ asset('storage/avatar.svg') }}" alt="profilePicture" class="rounded-circle" width="50" height="50" style="margin-right: 15px;">
                                        @endif

                                        <div style="max-width: calc(100% - 70px);">
                                            <strong>
                                                <a href="{{ auth()->check() && auth()->user()->id == $comment->user->id ? route('profile') : route('users.profile', $comment->user->id) }}" style="color: inherit; text-decoration: none;">
                                                    {{ $comment->user->name }}
                                                </a>
                                            </strong>
                                            <p class="card-text" style="word-wrap: break-word; overflow-wrap: break-word; margin: 5px 5px 5px 0px;">{{ $comment->comment }}</p>
                                        </div>
                                    </div>

                                    @if(auth()->id() == $comment->user_id)
                                        <div class="message-actions d-flex justify-content-end align-items-start mt-2 position-absolute end-0 bottom-0" style="right: 15px; bottom: 10px;">
                                            <!-- Edit Button -->
                                            <button class="btn btn-link btn-sm text-muted edit-comment-btn" data-comment-id="{{ $comment->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Delete Form -->
                                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="d-inline delete-comment-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-link btn-sm text-muted delete-comment-btn">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Edit Comment Form (Initially hidden) -->
                                        <form action="{{ route('comments.update', $comment->id) }}" method="POST" class="edit-comment-form mt-2 d-none">
                                            @csrf
                                            @method('PUT')
                                            <textarea class="form-control" name="comment" rows="2">{{ $comment->comment }}</textarea>
                                            <button type="submit" class="btn btn-success btn-sm mt-2">Save</button>
                                            <button type="button" class="btn btn-secondary btn-sm mt-2 cancel-edit">Cancel</button>
                                        </form>
                                    @endif

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Add Comment Section for Logged-in Users -->
                @auth
                    <div class="card mb-4 shadow-lg" style="border-radius: 15px; background-color: #f8f9fa;">
                        <div class="card-body">
                            <form action="{{ route('comments.store', $service->id) }}" method="POST" class="comment-form">
                                @csrf
                                @if ($errors->has('comment'))
                                    <span class="text-danger">{{ $errors->first('comment') }}</span>
                                @endif
                                <div class="form-group">
                                    <textarea name="comment" class="form-control" rows="4"></textarea>
                                </div>
                                <button type="submit" class="btn btn-custom mt-2">Submit Comment</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="card mb-4 shadow-lg" style="border-radius: 15px; background-color: #f8f9fa;">
                        <div class="card-body">
                            <p class="text-danger">You must be logged in to leave a comment.</p>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Edit comment functionality
            const editButtons = document.querySelectorAll('.edit-comment-btn');
            const cancelButtons = document.querySelectorAll('.cancel-edit');
            const deleteButtons = document.querySelectorAll('.delete-comment-btn');
            const deleteBtn = document.getElementById('delete-btn');
            const deleteForm = document.getElementById('delete-form');
            const editForms = document.querySelectorAll('.edit-comment-form');
            const editButtonsContainer = document.querySelectorAll('.mt-2');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const commentId = button.getAttribute('data-comment-id');
                    const commentForm = document.querySelector(`#comment-${commentId}`);
                    const form = commentForm.querySelector('.edit-comment-form');  // Find the form within the comment
                    const editDeleteContainer = commentForm.querySelector('.mt-2'); // The container with the edit and delete buttons

                    // Hide the edit/delete buttons and show the edit form
                    editDeleteContainer.classList.add('d-none');
                    form.classList.remove('d-none'); // Show the edit form
                    commentForm.querySelector('.card-text').classList.add('d-none'); // Hide the original comment text
                });
            });

            cancelButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const form = button.closest('form');
                    const commentForm = form.closest('.card-body');
                    const editDeleteContainer = commentForm.querySelector('.mt-2');

                    form.classList.add('d-none'); // Hide the edit form
                    editDeleteContainer.classList.remove('d-none'); // Show the edit/delete buttons again
                    commentForm.querySelector('.card-text').classList.remove('d-none'); // Show the original comment text again
                });
            });

            // Delete comment functionality
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    const form = button.closest('form');
                    if (confirm("Are you sure you want to delete this comment?")) {
                        form.submit(); // Delete the comment
                    }
                });
            });


            deleteBtn.addEventListener('click', function () {
                if (confirm("Are you sure you want to delete this service? This action cannot be undone.")) {
                    deleteForm.submit(); // Submit the form to delete the service
                }
            });

        });

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize the map
            var map = L.map('service-map').setView([{{ $service->latitude }}, {{ $service->longitude }}], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker([{{ $service->latitude }}, {{ $service->longitude }}]).addTo(map);
        });
    </script>
@endsection
