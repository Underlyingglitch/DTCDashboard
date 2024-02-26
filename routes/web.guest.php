<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::controller(AuthController::class)->name('auth.')->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'login_post');
    Route::get('/login_as', 'login_as')->name('login_as');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'register_post');
});
