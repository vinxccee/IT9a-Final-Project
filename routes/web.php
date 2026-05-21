<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\HousekeepingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('rooms', RoomController::class)->middleware('role:guest,receptionist,admin');
    Route::resource('bookings', BookingController::class)->middleware('role:guest,receptionist,admin');
    Route::post('bookings/{booking}/approve', [BookingController::class, 'approve'])
        ->name('bookings.approve')
        ->middleware('role:receptionist,admin');
    Route::patch('guests/{guest}/restore', [GuestController::class, 'restore'])
        ->name('guests.restore')
        ->middleware('admin')
        ->withTrashed();
    Route::resource('guests', GuestController::class)->middleware('role:receptionist,admin')->withTrashed(['show']);
    Route::resource('staff', StaffController::class)->middleware('admin');

    Route::prefix('operations')->name('operations.')->middleware('role:receptionist,admin')->group(function () {
        Route::get('/checkins', [CheckInController::class, 'index'])->name('checkins.index');
        Route::post('/bookings/{booking}/checkin', [CheckInController::class, 'store'])->name('bookings.checkin');
        Route::post('/bookings/{booking}/checkout', [CheckInController::class, 'checkout'])->name('bookings.checkout');
    });

    Route::middleware('role:guest,receptionist,admin')->group(function () {
        Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
        Route::post('/billing/payments', [BillingController::class, 'storePayment'])->name('billing.payments.store');
        Route::get('/billing/receipt/{payment}', [BillingController::class, 'showReceipt'])->name('billing.receipt.show');
    });

    Route::prefix('housekeeping')->name('housekeeping.')->middleware('role:receptionist,admin,housekeeping_staff')->group(function () {
        Route::get('/', [HousekeepingController::class, 'index'])->name('index');
        Route::post('/', [HousekeepingController::class, 'store'])->name('store');
        Route::patch('/tasks/{task}', [HousekeepingController::class, 'update'])->name('update');
        Route::patch('/tasks/{task}/approve', [HousekeepingController::class, 'approve'])->name('approve');
    });

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('users.role');
        Route::patch('/users/{user}/active', [AdminController::class, 'toggleActive'])->name('users.active');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    });
});

require __DIR__.'/auth.php';
