<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'login_post');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'register_post');
    Route::post('/more_details', 'more_details')->name('more_details');
});
