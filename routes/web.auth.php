<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\LocationController;

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('index');
    })->name('home');

    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::resource('competitions', CompetitionController::class);
    Route::resource('locations', LocationController::class);
});
