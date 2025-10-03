<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;


Route::get('/', function () {
    return view('landing');
})->name('landing');


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

 Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');


    Route::resource('users', UserController::class);
 Route::get('/payments', function () {
        return view('admin.payments.index');
    })->name('payments.index');

    // Inventory page
    Route::get('/inventory', function () {
        return view('admin.inventory.index');
    })->name('inventory.index');


    Route::get('/bookings', function () {
        return view('admin.bookings.index');
    })->name('bookings.index');
     Route::post('/bookings', function (\Illuminate\Http\Request $request) {
    
        dd($request->all());
    })->name('bookings.store');

  
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

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    

    Route::resource('users', UserController::class);
    
 
    Route::post('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
});

require __DIR__.'/auth.php';
