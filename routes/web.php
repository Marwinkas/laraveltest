<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [MainController::class, 'index'])->name('main');

Route::get('/book/{id}', [MainController::class, 'show'])->name('book.show');
Route::post('/comments', [MainController::class, 'store'])->name('comments.store');

Route::post('/cancel-reservation', [ReservationController::class, 'cancel'])->middleware('auth');

Route::get('/users', [ProfileController::class, 'index'])->name('users.index');

Route::post('/reserve', [ReservationController::class, 'store'])->name('reserve.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
