<?php

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    // User Management Routes
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');


    Route::get('/bookings', [BookingController::class, 'index'])->name('admin.bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('admin.bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('admin.bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('admin.bookings.show');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('admin.bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('admin.bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('admin.bookings.destroy');
    

    Route::get('/users/{user}/details', [BookingController::class, 'getUserDetails'])->name('admin.users.details');
});