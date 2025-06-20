<?php

// Import necessary controller classes
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/**
 * Routes for the application.
 *
 * These routes define all the available endpoints in the application.
 * Routes are grouped based on the user's authentication status (guest or authenticated).
 * Controllers are defined to handle requests and provide the necessary responses.
 */

// Home page route
Route::get('/', [HomeController::class, 'index'])->name('home'); // Home page for the application

// User profile route
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.profile'); // View profile of a specific user

// Routes for viewing services
Route::get('/services/all', [HomeController::class, 'services'])->name('services.all'); // View all services
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show'); // View a specific service

// Routes for guest users (not authenticated)
Route::middleware(['guest'])->group(function () {
    // Login and registration routes for guests
    Route::get('/login', [HomeController::class, 'login'])->name('login'); // Login page for users
    Route::post('/login', [UserController::class, 'loginPost'])->name('login.post'); // Handle login form submission
    Route::get('/register', [HomeController::class, 'register'])->name('register'); // Registration page
    Route::post('/register', [UserController::class, 'registerPost'])->name('register.post'); // Handle registration form submission
});

// Routes for authenticated users
Route::middleware(['auth'])->group(function () {
    // Routes for creating services
    Route::get('/create', [ServiceController::class, 'createService'])->name('services.create'); // Form to create a new service
    Route::post('/create', [ServiceController::class, 'createServicePost'])->name('services.create.post'); // Submit new service

    // Profile routes for authenticated users
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile'); // View the user's profile
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit'); // Edit the user's profile
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update'); // Update the user's profile
    Route::post('/logout', [UserController::class, 'logout'])->name('logout'); // Logout the user

    // Message routes for authenticated users
    Route::get('/conversations', [MessageController::class, 'index'])->name('messages.index'); // View all conversations
    Route::post('/conversations/store', [MessageController::class, 'store'])->name('messages.store'); // Create a new conversation
    Route::get('/conversations/{conversationId}', [MessageController::class, 'showMessages'])->name('messages.show'); // View a specific conversation
    Route::post('/messages/{messageId}/reply', [MessageController::class, 'replyMessage'])->name('messages.reply'); // Reply to a specific message
    Route::put('/messages/{messageId}/update', [MessageController::class, 'updateMessage'])->name('messages.update'); // Update a specific message
    Route::delete('/messages/{messageId}/delete', [MessageController::class, 'deleteMessage'])->name('messages.delete'); // Delete a specific message
    Route::delete('/conversations/{conversationId}/delete', [MessageController::class, 'deleteConversation'])->name('messages.delete.conversation'); // Delete a specific conversation

    // Service management routes (comments, reviews, editing, updating, deleting services)
    Route::post('/services/{service}/comment', [CommentController::class, 'store'])->name('comments.store'); // Add a comment to a service
    Route::post('/reviews/{userId}', [ReviewController::class, 'store'])->name('reviews.store'); // Add a review for a user
    Route::get('/services/{id}/edit', [ServiceController::class, 'editService'])->name('services.edit'); // Edit a service
    Route::post('/services/{id}/update', [ServiceController::class, 'updateService'])->name('services.update'); // Update a service
    Route::delete('/services/{id}', [ServiceController::class, 'deleteService'])->name('services.delete'); // Delete a service

    // Reference routes for users
    Route::get('/profile/{userId}/references', [ReferenceController::class, 'show'])->name('profile.references'); // View references for a user
    Route::post('/references', [ReferenceController::class, 'store'])->name('references.store'); // Add a new reference
    Route::get('/references/{id}/edit', [ReferenceController::class, 'edit'])->name('references.edit'); // Edit an existing reference
    Route::put('/references/{id}/update', [ReferenceController::class, 'update'])->name('references.update'); // Update a reference
    Route::delete('/references/{id}/delete', [ReferenceController::class, 'destroy'])->name('references.delete'); // Delete a reference

    // Comment management routes (edit, delete)
    Route::put('/comments/{commentId}', [CommentController::class, 'update'])->name('comments.update'); // Edit a comment
    Route::delete('/comments/{commentId}', [CommentController::class, 'destroy'])->name('comments.destroy'); // Delete a comment

    // Notification management routes
    Route::get('/notifications/{notificationId}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead'); // Mark a notification as read
    Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead'); // Mark all notifications as read
    Route::post('/notifications/delete-all', [NotificationController::class, 'deleteAll'])->name('notifications.deleteAll'); // Delete all notifications
});
