<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('index');

// AUTH
Route::group(['prefix' => 'auth'], function () {
    Route::get('/registration', [RegisterController::class, 'showRegisterPage'])->name('auth.registration');
    Route::post('/registration', [RegisterController::class, 'registerUser'])->name('auth.registration.user');

    Route::get('/login', [LoginController::class, 'showLoginPage'])->name('auth.login');
    Route::post('/login', [LoginController::class, 'loginUser'])->name('auth.login.user');

})
    ->middleware('guest');

Route::post('/logout', LogoutController::class)->name('auth.logout')
->middleware('auth');
