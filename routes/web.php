<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

// AUTH
Route::group('auth', function () {
    Route::get('/register', [RegisterController::class, 'showRegisterPage'])->name('register');
    Route::post('/register', [RegisterController::class, 'registerUser'])->name('register.user');

    Route::get('/login', [RegisterController::class, 'showLoginPage'])->name('login');
});
