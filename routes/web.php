<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\RoomController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::middleware(['auth'])->group(function () {
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::post('/rooms/{id}/status', [RoomController::class, 'updateStatus'])->name('rooms.updateStatus');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
  
    Route::get('/reservations/data', [ReservationController::class, 'getReservationsData'])->name('reservations.data');
    
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::get('/users/{user}', action: [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/stay-history', [UserController::class, 'getStayHistory'])->name('users.stay-history');

    Route::get('/bookings', [ReservationController::class, 'index'])->name('bookings.index');



    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');

    Route::get('/payments', function () {
        return view('admin.payments.index');
    })->name('payments.index');

    Route::get('/inventory', function () {
        return view('admin.inventory.index');
    })->name('inventory.index');

    Route::get('/reports', function () {
        return view('admin.reports.index');
    })->name('reports.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';