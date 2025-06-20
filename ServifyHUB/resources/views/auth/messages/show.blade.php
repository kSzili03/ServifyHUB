@extends('layouts.default') <!-- Extends the default layout for the page -->

@section('title', 'Chat Conversation') <!-- Sets the title of the chat conversation page -->

@section('content')
    <div class="container mt-5">
        <!-- Display success message if message update or delete is successful -->
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

        <!-- Display each message in the conversation -->
        @foreach($conversation as $message)
            <div class="card mb-3" style="border-radius: 15px; background-color: #f8f9fa;">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-start">
                        <!-- Sender's Profile Picture -->
                        <div class="me-3">
                            <img src="{{ $message->sender->profile_picture ? asset('storage/' . $message->sender->profile_picture) : asset('storage/avatar.svg') }}"
                                 alt="Profile Picture" class="rounded-circle" width="50" height="50">
                        </div>
                        <!-- Sender's Name and Message -->
                        <div style="max-width: calc(100% - 70px);">
                            <strong class="d-block text-truncate" style="white-space: normal; overflow: hidden; text-overflow: ellipsis; word-wrap: break-word; word-break: break-word;">
                                <!-- Link to sender's profile, check if it's the logged-in user -->
                                <a href="{{ auth()->check() && auth()->user()->id == $message->sender->id ? route('profile') : route('users.profile', $message->sender->id) }}" style="color: inherit; text-decoration: none;">
                                    {{ $message->sender->name }}
                                </a>
                            </strong>

                            <!-- Display message content or edit form -->
                            @if(auth()->id() == $message->sender_id)
                                <div id="message-{{ $message->id }}" class="message-content">
                                    <p class="card-text message-text" style="word-wrap: break-word; overflow-wrap: break-word; margin: 5px 5px 5px 0px;">{{ $message->message }}</p>

                                    <!-- Edit and Delete icons -->
                                    <div class="message-actions d-flex justify-content-end align-items-start mt-2 position-absolute end-0 bottom-0">
                                        <!-- Edit Icon -->
                                        <button class="btn btn-link btn-sm text-muted edit-message-btn" data-message-id="{{ $message->id }}">
                                            <i class="fas fa-edit"></i> <!-- Edit Icon -->
                                        </button>
                                        <form action="{{ route('messages.delete', $message->id) }}" method="POST" class="d-inline delete-message-form">
                                            @csrf
                                            @method('DELETE')
                                            <!-- Delete Icon -->
                                            <button type="button" class="btn btn-link btn-sm text-muted delete-message-btn">
                                                <i class="fas fa-trash-alt"></i> <!-- Trash Icon -->
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Edit Form (Initially hidden) -->
                                    <form action="{{ route('messages.update', $message->id) }}" method="POST" class="edit-message-form mt-2 d-none">
                                        @csrf
                                        @method('PUT') <!-- Use PUT for update -->
                                        <textarea class="form-control" name="message" rows="2">{{ $message->message }}</textarea>
                                        <button type="submit" class="btn btn-success btn-sm mt-2">Save</button>
                                        <button type="button" class="btn btn-secondary btn-sm mt-2 cancel-edit">Cancel</button>
                                    </form>
                                </div>
                            @else
                                <!-- Display message content without edit form -->
                                <p class="card-text" style="word-wrap: break-word; overflow-wrap: break-word; margin: 5px 5px 5px 0px;">{{ $message->message }}</p>
                            @endif
                        </div>
                    </div>
                    <!-- Message Sent Time -->
                    <p class="text-muted bottom-0 end-0 mb-2 me-2" style="font-size: 0.8em; margin: 8px">
                        Sent on: {{ $message->created_at->format('F j, Y, g:i A') }}
                    </p>
                </div>
            </div>
        @endforeach

        <!-- Reply Form -->
        <form action="{{ route('messages.reply', $conversation->first()->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <textarea name="message" class="form-control" rows="4" placeholder="Your reply..."></textarea>
                @if ($errors->has('message'))
                    <span class="text-danger">{{ $errors->first('message') }}</span>
                @endif
            </div>
            <button type="submit" class="btn btn-primary mt-3">Send Reply</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Show the edit form when the "Edit" button is clicked
            document.querySelectorAll('.edit-message-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const messageId = this.getAttribute('data-message-id');
                    const messageCard = document.querySelector(`#message-${messageId}`);
                    const messageContent = messageCard.querySelector('.message-text');
                    const editForm = messageCard.querySelector('.edit-message-form');
                    const editDeleteContainer = messageCard.querySelector('.message-actions');

                    // Hide the original message and show the edit form
                    messageContent.classList.add('d-none');  // Hide message text
                    editForm.classList.remove('d-none');    // Show edit form
                    editDeleteContainer.classList.add('d-none'); // Hide edit/delete icons
                });
            });

            // Cancel the edit and hide the edit form
            document.querySelectorAll('.cancel-edit').forEach(button => {
                button.addEventListener('click', function () {
                    const messageCard = this.closest('.edit-message-form').closest('.message-content');
                    const messageContent = messageCard.querySelector('.message-text');
                    const editForm = messageCard.querySelector('.edit-message-form');
                    const editDeleteContainer = messageCard.querySelector('.message-actions');

                    editForm.classList.add('d-none'); // Hide the edit form
                    messageContent.classList.remove('d-none'); // Show the original message
                    editDeleteContainer.classList.remove('d-none'); // Show the edit/delete icons again
                });
            });

            // Delete message functionality
            document.querySelectorAll('.delete-message-btn').forEach(button => {
                button.addEventListener('click', function () {
                    if (confirm("Are you sure you want to delete this message?")) {
                        this.closest('form').submit(); // Delete the message
                    }
                });
            });
        });
    </script>

@endsection
