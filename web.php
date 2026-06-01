<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\GuestController;

/*
|--------------------------------------------------------------------------
| Начална страница
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $rooms = \App\Models\Room::where('status', 'available')
        ->latest()
        ->take(6)
        ->get();

    return view('welcome', compact('rooms'));
})->name('home');

/*
|--------------------------------------------------------------------------
| Маршрути за резервации — достъпни само за логнати потребители
|--------------------------------------------------------------------------
*/
Route::resource('reservations', ReservationController::class)
    ->middleware('auth')
    ->names('reservations');

/*
|--------------------------------------------------------------------------
| Маршрути за стаи и гости — само за администратори
|--------------------------------------------------------------------------
*/
Route::resource('rooms', RoomController::class)
    ->middleware('auth')
    ->names('rooms');

Route::resource('guests', GuestController::class)
    ->middleware('auth')
    ->names('guests');
