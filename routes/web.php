<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingInvoiceController;

Route::get('/', fn () => view('welcome'));

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    Route::middleware('role')->group(function () {
         Route::resource('room-types', RoomTypeController::class)->except(['index', 'show']);
        Route::resource('rooms', RoomController::class)->except(['show']);
       
        Route::post('bookings/{booking}/confirm', [BookingController::class, 'confirm'])
    ->name('bookings.confirm');
        Route::post('bookings/{booking}/check-in', [BookingController::class, 'checkIn'])->name('bookings.checkin');
        Route::post('bookings/{booking}/check-out', [BookingController::class, 'checkOut'])->name('bookings.checkout');
        Route::post('/bookings/{booking}/check-in', [BookingController::class, 'checkIn'])->name('bookings.checkin');
    Route::post('/bookings/{booking}/check-out', [BookingController::class, 'checkOut'])->name('bookings.checkout');
    });

    Route::get('bookings/{booking}/invoice', [BookingInvoiceController::class, 'download'])
        ->name('bookings.invoice.download');
     Route::resource('room-types', RoomTypeController::class)->only(['index', 'show']);
    Route::resource('bookings', BookingController::class);
});

require __DIR__.'/auth.php';
