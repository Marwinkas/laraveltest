<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Возвращает данные аутентифицированного пользователя
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
