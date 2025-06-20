@extends('layouts.default') <!-- Extends the default layout for the page -->

@section('title', 'Conversations') <!-- Sets the title of the conversation page -->

@section('content')
    <div class="container mt-5">
        <h3 class="text-center mb-4">Messages</h3>

        @if($conversations->isEmpty())
            <div class="d-flex justify-content-center mt-4">
                <div class="border border-secondary text-secondary px-4 py-2 rounded d-flex align-items-center" style="min-width: 150px;">
                    <i class="fa fa-envelope-open me-2"></i> No messages
                </div>
            </div>
        @else
            @foreach($conversations as $conversation)
                @php
                    $firstMessage = $conversation->first();
                    $hasUnreadMessages = $conversation->where('receiver_id', auth()->id())->where('read', false)->count() > 0;
                    $hasUnreadReplies = $conversation->where('is_reply', true)->where('receiver_id', auth()->id())->where('read', false)->count() > 0;
                    $isSender = auth()->id() === $firstMessage->sender_id;
                    $otherUser = ($firstMessage->sender_id == auth()->id()) ? $firstMessage->receiver : $firstMessage->sender;
                @endphp

                <div class="card mb-3 {{ ($hasUnreadMessages || $hasUnreadReplies) && !$isSender ? 'border-secondary' : 'bg-light' }}" id="conversation-{{ $conversation->first()->conversation_id }}">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset($otherUser->profile_picture ? 'storage/' . $otherUser->profile_picture : 'storage/avatar.svg') }}" alt="Profile Picture" class="rounded-circle" width="50" height="50">
                                <div class="ms-3">
                                    <strong>
                                        <a href="{{ auth()->check() && auth()->user()->id == $otherUser->id ? route('profile') : route('users.profile', $otherUser->id) }}" style="color: inherit; text-decoration: none;">
                                            {{ $otherUser->name }}
                                        </a>
                                    </strong>
                                    <div>
                                        <small>Subject: {{ $firstMessage->subject }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex">
                                <!-- Open button with icon -->
                                <a href="{{ route('messages.show', $firstMessage->conversation_id) }}" class="btn btn-outline-secondary btn-sm conversation-link me-2" title="Open Conversation">
                                    <i class="fa fa-comment"></i> <!-- Comment Icon for opening -->
                                </a>

                                <!-- Delete button -->
                                <form action="{{ route('messages.delete.conversation', $firstMessage->conversation_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this conversation?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete Conversation">
                                        <i class="fa fa-trash"></i> <!-- Trash Icon -->
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const conversationLinks = document.querySelectorAll('.conversation-link');

            conversationLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    const conversationId = link.closest('.card').id.split('-')[1];
                    const form = document.createElement('form');
                    form.setAttribute('method', 'POST');
                    form.setAttribute('action', '{{ route('messages.show', '') }}' + '/' + conversationId);
                    document.body.appendChild(form);
                    form.submit();
                });
            });
        });
    </script>
@endsection
