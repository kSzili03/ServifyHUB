@extends("layouts.default") <!-- Extends the default layout for the page -->

@section("title", $user->name) <!-- Sets the title for the profile page -->

@section("content")
    <div class="container mt-5">
        <div class="row">
            <!-- Main Profile Card -->
            <div class="col-md-12">
                <div class="card mb-4 shadow-lg" style="border-radius: 15px; background-color: #f8f9fa;">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card-body">
                        <!-- Profile Info -->
                        <div class="row">
                            <div class="col-12 col-md-4 text-center p-4">
                                @if($user->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="profilePicture" class="rounded-circle" width="150" height="150" style="object-fit: cover;">
                                @else
                                    <img src="{{ asset('storage/avatar.svg') }}" alt="profilePicture" class="rounded-circle" width="150" height="150">
                                @endif
                                <h4 class="mt-3">{{ $user->name }}</h4>
                                <p>Email: {{ $user->email }}</p>

                                <!-- Edit Profile Button -->
                                @if(auth()->check() && auth()->user()->id == $user->id)
                                    <a href="{{ route('profile.edit') }}" class="btn btn-primary mt-3">Edit Profile</a>
                                @endif

                                <div class="mt-4" style="border-top: 2px solid #9E9E9E; padding-top: 15px;">
                                    <h5 class="text-center">Average Rating</h5>
                                    <p class="text-center">
                                        @if($user->average_rating)
                                            <span>{{ number_format($user->average_rating, 1) }}</span>
                                            <!-- Display stars based on the average rating -->
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

                            <!-- Profile Navigation Tabs -->
                            <div class="col-12 col-md-8">
                                <!-- Profile Navigation Buttons (Styled) -->
                                <div class="d-flex flex-wrap justify-content-between mb-4">
                                    <button id="servicesTab" class="btn btn-outline-primary flex-fill mx-2" style="min-width: 150px;">Services</button>
                                    <button id="reviewsTab" class="btn btn-outline-primary flex-fill mx-2" style="min-width: 150px;">Reviews</button>
                                    <button id="referencesTab" class="btn btn-outline-primary flex-fill mx-2" style="min-width: 150px;">References</button>
                                    @if(auth()->check() && auth()->user()->id != $user->id)
                                        <button id="contactTab" class="btn btn-outline-primary flex-fill mx-2" style="min-width: 150px;">Contact</button>
                                    @endif
                                </div>

                                <!-- Dynamic Content Area -->
                                <div id="dynamicContent">
                                    <div id="servicesContent">
                                        @forelse($user->services ?? [] as $service)
                                            <div class="service-box mb-3 p-3" style="border: 2px solid #d3d3d3; border-radius: 10px; background-color: #e9ecef;">
                                                <h5>
                                                    <a href="{{ route('services.show', $service->id) }}" class="text-decoration-none" style="color: #007bff;">
                                                        {{ $service->name }}
                                                    </a>
                                                </h5>

                                                <!-- Show Edit and Delete buttons if the logged-in user is the profile owner -->
                                                @if(auth()->check() && auth()->user()->id == $user->id)
                                                    <div class="mt-2 text-end">
                                                        <a href="{{ route('services.edit', $service->id) }}" class="btn btn-outline-secondary btn-sm">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>

                                                        <!-- Delete Form with Button -->
                                                        <form action="{{ route('services.delete', $service->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this service? This action cannot be undone.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-outline-danger btn-sm">
                                                                <i class="fas fa-trash-alt me-1"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        @empty
                                            <p>No services available yet.</p>
                                        @endforelse

                                        <!-- Add New Service Button (only show if the logged-in user is the profile owner) -->
                                        @if(auth()->check() && auth()->user()->id == $user->id)
                                            <div class="sticky-bottom mb-3">
                                                <div class="text-center">
                                                    <a href="{{ route('services.create') }}" class="btn btn-primary mt-3" style="margin-bottom: 15px">
                                                        <i class="bi bi-plus-circle"></i> New Service
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div id="reviewsContent" style="display: none;">
                                        <!-- Reviews section -->
                                        @forelse($user->reviews ?? [] as $review)
                                            <div class="review-box mb-3 p-3 position-relative" style="border: 2px solid #d3d3d3; border-radius: 10px; background-color: #e9ecef;">
                                                <div class="d-flex align-items-start">
                                                    <!-- Reviewer Image -->
                                                    <div class="me-3">
                                                        @if($review->user->profile_picture)
                                                            <img src="{{ asset('storage/' . $review->user->profile_picture) }}" alt="reviewerImage" class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                                        @else
                                                            <img src="{{ asset('storage/avatar.svg') }}" alt="profilePicture" class="rounded-circle" width="50" height="50">
                                                        @endif
                                                    </div>
                                                    <div style="max-width: calc(100% - 70px);">
                                                        <strong>{{ $review->rating }} stars</strong>
                                                        <p class="card-text" style="word-wrap: break-word; overflow-wrap: break-word; margin:5px 5px 5px 0px;">{{ $review->review }}</p>
                                                    </div>
                                                </div>

                                                <!-- Timestamp in the bottom-right corner -->
                                                <p class="text-muted bottom-0 end-0 mb-2 me-2" style="font-size: 0.8em; margin: 8px">
                                                    Reviewed on: {{ $review->created_at->format('F j, Y, g:i A') }}
                                                </p>
                                            </div>
                                        @empty
                                            <p>No reviews available yet.</p>
                                        @endforelse

                                        @if(auth()->check())
                                            @if(auth()->user()->id != $user->id)
                                                <div class="mt-4">
                                                    <h4 class="text-center">Leave a Review</h4>
                                                    <form action="{{ route('reviews.store', $user->id) }}" method="POST">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="rating">Rating</label>
                                                            <select name="rating" class="form-control" id="rating" required>
                                                                <option value="" disabled selected>Choose a rating</option>
                                                                <option value="1">1 Star</option>
                                                                <option value="2">2 Stars</option>
                                                                <option value="3">3 Stars</option>
                                                                <option value="4">4 Stars</option>
                                                                <option value="5">5 Stars</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="comment">Comment</label>
                                                            <textarea name="comment" class="form-control" id="comment" rows="4"></textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary w-100 mt-3">Submit Review</button>
                                                    </form>
                                                </div>
                                            @endif
                                        @else
                                            <p class="text-center text-danger mt-3">You must be logged in to leave a review.</p>
                                        @endif
                                    </div>

                                    <!-- References Section -->
                                    <div id="referencesContent" style="display: none;">
                                        @if($user->references->isEmpty())
                                            <p>No references available yet.</p>
                                        @else
                                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                                                @foreach($user->references as $index => $reference)
                                                    <div class="col mb-4">
                                                        <img src="{{ asset('storage/' . $reference->image_path) }}" alt="Reference Image"
                                                             class="img-fluid rounded"
                                                             style="object-fit: cover; cursor: pointer; height: 200px; width: 100%;"
                                                             data-bs-toggle="modal" data-bs-target="#referenceModal{{ $index }}"
                                                             data-title="{{ $reference->title }}" data-description="{{ $reference->description }}"
                                                             data-image="{{ asset('storage/' . $reference->image_path) }}"
                                                             data-index="{{ $index }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Add Reference Button (only show if the logged-in user is the profile owner) -->
                                        @if(auth()->check() && auth()->user()->id == $user->id)
                                            <div class="sticky-bottom mt-3">
                                                <div class="text-center">
                                                    <button id="addReferenceTab" class="btn btn-primary mt-3" style="margin-bottom: 15px" data-bs-toggle="modal" data-bs-target="#addReferenceModal">
                                                        <i class="bi bi-plus-circle"></i> Add Reference
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Modal for reference carousel (for all references) -->
                                    @foreach($user->references as $index => $reference)
                                        <div class="modal fade" id="referenceModal{{ $index }}" tabindex="-1" aria-labelledby="referenceModalLabel{{ $index }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Image and Description carousel for all references -->
                                                        <div id="modalCarousel{{ $index }}" class="carousel slide" data-bs-ride="carousel">
                                                            <div class="carousel-inner">
                                                                @foreach($user->references as $innerIndex => $innerReference)
                                                                    <div class="carousel-item {{ $innerIndex == $index ? 'active' : '' }}">
                                                                        <img src="{{ asset('storage/' . $innerReference->image_path) }}" alt="Reference Image"
                                                                             class="d-block w-100" style="max-height: 500px; object-fit: cover;">
                                                                        <div class="carousel-caption d-none d-md-block" style="bottom: 20px; left: 10px; right: 10px; text-align: center; background-color: rgba(0, 0, 0, 0.5); padding: 10px; border-radius: 5px;">
                                                                            <h5>{{ $innerReference->title }}</h5>
                                                                            <p>{{ $innerReference->description }}</p>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <!-- Carousel Controls -->
                                                            <button class="carousel-control-prev" type="button" data-bs-target="#modalCarousel{{ $index }}" data-bs-slide="prev">
                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                <span class="visually-hidden">Previous</span>
                                                            </button>
                                                            <button class="carousel-control-next" type="button" data-bs-target="#modalCarousel{{ $index }}" data-bs-slide="next">
                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                <span class="visually-hidden">Next</span>
                                                            </button>
                                                        </div>

                                                        @if(auth()->check() && auth()->user()->id == $user->id)
                                                            <div class="mt-2 d-flex justify-content-between align-items-center">
                                                                <!-- Edit Button (left) -->
                                                                <a href="{{ route('references.edit', $reference->id) }}" class="btn btn-outline-secondary btn-sm">
                                                                    <i class="fas fa-edit me-1"></i> Edit
                                                                </a>

                                                                <!-- Delete Button (right) -->
                                                                <form action="{{ route('references.delete', $reference->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this reference?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-outline-danger btn-sm">
                                                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Modal for Adding a Reference -->
                                    <div class="modal fade" id="addReferenceModal" tabindex="-1" aria-labelledby="addReferenceModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addReferenceModalLabel">Add New Reference</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Add Reference Form -->
                                                    <form action="{{ route('references.store') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="title" class="form-label">Title</label>
                                                            <input type="text" name="title" id="title" class="form-control">
                                                            @if ($errors->has('title'))
                                                                <span class="text-danger">{{ $errors->first('title') }}</span>
                                                            @endif
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="description" class="form-label">Description</label>
                                                            <textarea name="description" id="description" rows="4" class="form-control"></textarea>
                                                            @if ($errors->has('description'))
                                                                <span class="text-danger">{{ $errors->first('description') }}</span>
                                                            @endif
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="image" class="form-label">Reference Image</label>
                                                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                                            @if ($errors->has('image'))
                                                                <span class="text-danger">{{ $errors->first('image') }}</span>
                                                            @endif
                                                        </div>

                                                        <button type="submit" class="btn btn-primary w-100 mt-3">Submit</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Form Content -->
                                    <div id="contactContent" style="display: none;">
                                        <h3 class="text-center mb-4">Contact {{ $user->name }}</h3>
                                        @if(auth()->check())
                                            @if(auth()->user()->id != $user->id)
                                                <form action="{{ route('messages.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                                                    <div class="form-group">
                                                        <label for="subject">Subject</label>
                                                        <input type="text" name="subject" class="form-control" id="subject">
                                                        @if ($errors->has('subject'))
                                                            <span class="text-danger">{{ $errors->first('subject') }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="message">Message</label>
                                                        <textarea name="message" class="form-control" id="message" rows="4"></textarea>
                                                        @if ($errors->has('message'))
                                                            <span class="text-danger">{{ $errors->first('message') }}</span>
                                                        @endif
                                                    </div>
                                                    <button type="submit" class="btn btn-custom w-100 mt-3">Send Message</button>
                                                </form>
                                            @else
                                                <p class="text-center text-muted mt-3">You cannot send a message to yourself.</p>
                                            @endif
                                        @else
                                            <p class="text-center text-danger mt-3">You must be logged in to send a message.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to handle tab switching -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check if elements exist before adding events to them
            const servicesTab = document.getElementById('servicesTab');
            const reviewsTab = document.getElementById('reviewsTab');
            const referencesTab = document.getElementById('referencesTab');
            const contactTab = document.getElementById('contactTab');
            const deleteForm = document.getElementById('deleteForm');
            const deleteBtn = document.getElementById('deleteBtn');

            // If the 'servicesTab' element exists, add event listener
            if (servicesTab) {
                servicesTab.addEventListener('click', function () {
                    document.getElementById('servicesContent').style.display = 'block';
                    document.getElementById('reviewsContent').style.display = 'none';
                    document.getElementById('referencesContent').style.display = 'none';
                    document.getElementById('contactContent').style.display = 'none';
                    this.classList.add('active');
                    if (reviewsTab) reviewsTab.classList.remove('active');
                    if (referencesTab) referencesTab.classList.remove('active');
                    if (contactTab) contactTab.classList.remove('active');
                });
            }

            // If the 'reviewsTab' element exists, add event listener
            if (reviewsTab) {
                reviewsTab.addEventListener('click', function () {
                    document.getElementById('servicesContent').style.display = 'none';
                    document.getElementById('reviewsContent').style.display = 'block';
                    document.getElementById('referencesContent').style.display = 'none';
                    document.getElementById('contactContent').style.display = 'none';
                    this.classList.add('active');
                    if (servicesTab) servicesTab.classList.remove('active');
                    if (referencesTab) referencesTab.classList.remove('active');
                    if (contactTab) contactTab.classList.remove('active');
                });
            }

            // If the 'referencesTab' element exists, add event listener
            if (referencesTab) {
                referencesTab.addEventListener('click', function () {
                    document.getElementById('servicesContent').style.display = 'none';
                    document.getElementById('reviewsContent').style.display = 'none';
                    document.getElementById('referencesContent').style.display = 'block';
                    document.getElementById('contactContent').style.display = 'none';
                    this.classList.add('active');
                    if (servicesTab) servicesTab.classList.remove('active');
                    if (reviewsTab) reviewsTab.classList.remove('active');
                    if (contactTab) contactTab.classList.remove('active');
                });
            }

            // If the 'contactTab' element exists, add event listener
            if (contactTab) {
                contactTab.addEventListener('click', function () {
                    document.getElementById('servicesContent').style.display = 'none';
                    document.getElementById('reviewsContent').style.display = 'none';
                    document.getElementById('referencesContent').style.display = 'none';
                    document.getElementById('contactContent').style.display = 'block';
                    this.classList.add('active');
                    if (servicesTab) servicesTab.classList.remove('active');
                    if (reviewsTab) reviewsTab.classList.remove('active');
                    if (referencesTab) referencesTab.classList.remove('active');
                });
            }

            // Handle the delete action with confirmation before submitting the form
            if (deleteBtn) {
                deleteForm.addEventListener('submit', function (event) {
                    event.preventDefault();  // Prevent the form from submitting automatically

                    // Ask the user if they are sure about deleting the service
                    const confirmDelete = confirm("Are you sure you want to delete this service? This action cannot be undone.");
                    if (confirmDelete) {
                        // If the user presses OK, submit the form
                        deleteForm.submit();  // Now actually submit the form
                    }
                });
            }

        });
    </script>
@endsection
