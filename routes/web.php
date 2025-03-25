<?php

/*
    Summary:
    This file defines the web routes for the Instagram-like Laravel application.
    Routes include:
      - A default route that returns the registration view.
      - Dashboard routes for authenticated and verified users.
      - Profile routes for viewing, editing, updating, and deleting the profile.
      - Routes to list a profile's followers and followings.
      - Post-related routes for creating, storing, and displaying posts.
      - Follow/unfollow routes for user profiles.
      - Additional authentication routes.
*/

// Import necessary classes
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\PostController;

// Default route returning the registration view
Route::get('/', function () {
    return view('auth.register'); // Show the registration page
});

// Dashboard routes secured by auth and verified middleware
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard main view route
    Route::get('/dashboard', [ProfileController::class, 'index'])->name('dashboard');
    // Dashboard profile detailed view, accepts a profile parameter
    Route::get('/dashboard/{profile}', [ProfileController::class, 'show']);
});

// Profile and post related routes secured by auth middleware
Route::middleware('auth')->group(function () {
    // Profile editing routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route to update profile image only
    Route::post('/profile/image', [ProfileController::class, 'updateImage'])->name('profile.image.update');
    // Profile deletion route
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Viewing a specific profile by its identifier
    Route::get('/profile/{profile}', [ProfileController::class, 'show'])->name('profile.show');

    // Routes to display profile's followers and following lists
    Route::get('/profile/{profile}/followers', [ProfileController::class, 'followers'])->name('profile.followers');
    Route::get('/profile/{profile}/following', [ProfileController::class, 'followings'])->name('profile.followings');

    // Post creation and submission routes (using ProfileController)
    Route::get('/posts/create', [ProfileController::class, 'createPost'])->name('posts.create');
    Route::post('/posts', [ProfileController::class, 'storePost'])->name('posts.store');
    // Legacy route to display a single post using ProfileController
    Route::get('/posts/{id}', [ProfileController::class, 'showPost'])->name('posts.show');

    // Route to display photos
    Route::get("/photos", [ProfileController::class, 'photos'])->name('photos');

    // RESTful resource routes for posts using PostController
    Route::resource('posts', PostController::class);
    // Additional route to show a single post using PostController (duplicate naming when used with resource)
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
});

// Follow/Unfollow routes for profiles
// NOTE: There are duplicate definitions below; one uses the "photo" method and the other "follow" method.
Route::post('/follow/{profile}', [ProfileController::class, 'photo'])
    ->middleware('auth')
    ->name('follow');

Route::post('/follow/{profile}', [App\Http\Controllers\ProfileController::class, 'follow'])
    ->middleware('auth')
    ->name('profile.follow');

// User search and list routes
Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
Route::get('/users/search', [App\Http\Controllers\UserController::class, 'search'])->name('users.search');

// Include additional authentication routes from the auth.php file
require __DIR__ . '/auth.php';
