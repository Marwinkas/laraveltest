<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

// Главная страница
Route::get('/', [MainController::class, 'index'])->name('main');

// Просмотр информации о книге
Route::get('/book/{id}', [BookController::class, 'show'])->name('book.show');



// Административные маршруты
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'show'])->name('admin.show');
    Route::post('/admin', [AdminController::class, 'store'])->name('admin.store');
    Route::put('/admin', [AdminController::class, 'update'])->name('admin.password.update');
    Route::delete('/admin', [AdminController::class, 'destroy'])->name('admin.destroy');
});

// маршруты Библиотекаря
Route::middleware(['auth', 'role:librarian,admin'])->group(function () {
    Route::get('/library', [BookController::class, 'library'])->name('library.show');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::delete('/books', [BookController::class, 'destroy'])->name('books.destroy');
    Route::post('/reservations/{reservation}/deliver', [ReservationController::class, 'deliver'])->name('reservations.deliver');
    Route::post('/reservations/{reservation}/take', [ReservationController::class, 'take'])->name('reservations.take');


});

// Группа маршрутов для аутентифицированных пользователей
Route::middleware('auth')->group(function () {
    // Добавление комментариев
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');
    // Бронирование книг
    Route::post('/reserve', [ReservationController::class, 'store'])->name('reserve.store');
    Route::post('/cancel-reservation', [ReservationController::class, 'cancel']);

    // Управление профилем пользователя
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';
