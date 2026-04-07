<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Public home
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rooms — staff and admin can manage
    Route::resource('rooms', RoomController::class)->middleware('staff_or_admin');

    // Bookings — staff and admin
    Route::resource('bookings', BookingController::class)->middleware('staff_or_admin');

    // Guests — staff and admin
    Route::resource('guests', GuestController::class)->middleware('staff_or_admin');

    // Staff — admin only
    Route::resource('staff', StaffController::class)->middleware('admin');

    // Admin panel
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('users.role');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    });
});

require __DIR__.'/auth.php';