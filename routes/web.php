<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;


Route::get('/', function () {
    return view('landing');
})->name('landing');


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Users CRUD
    Route::resource('users', UserController::class);

    // Payments page
    Route::get('/payments', function () {
        return view('admin.payments');
    })->name('payments');


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
