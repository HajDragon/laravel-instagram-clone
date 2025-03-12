<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

Route::get('/', function () {
    return view('auth.register');
});

// Use the ProfileController for dashboard routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/{profile}', [ProfileController::class, 'show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/image', [ProfileController::class, 'updateImage'])->name('profile.image.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{profile}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/{profile}/followers', [ProfileController::class, 'followers'])->name('profile.followers');
    Route::get('/profile/{profile}/following', [ProfileController::class, 'followings'])->name('profile.followings');
});

// Add follow/unfollow route
Route::post('/follow/{profile}', [ProfileController::class, 'follow'])
    ->middleware('auth')
    ->name('follow');

require __DIR__ . '/auth.php';
