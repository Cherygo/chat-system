<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Chat\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('index');

// AUTH
Route::prefix('auth')->middleware('guest')->group(function () {
    Route::get('/registration', [RegisterController::class, 'showRegisterPage'])->name('registration');
    Route::post('/registration', [RegisterController::class, 'registerUser'])->name('registration.user');

    Route::get('/login', [LoginController::class, 'showLoginPage'])->name('login');
    Route::post('/login', [LoginController::class, 'loginUser'])->name('login.user');

});

Route::post('/logout', LogoutController::class)->name('logout')
    ->middleware('auth');

//CHATS
Route::prefix('chat')->middleware('auth')->group(function () {
   Route::get('/index', [ChatController::class, 'index'])->name('chat.index');
   Route::get('/{chat}', [ChatController::class, 'show'])->name('chat.show');
   Route::post('/{chat}', [ChatController::class, 'store'])->name('chat.store');
});
