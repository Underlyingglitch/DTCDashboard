<?php

use App\Http\Controllers\JuryTafelController;
use App\Http\Controllers\DeviceAuthController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Request;


Route::middleware('auth')->controller(JuryTafelController::class)
    // Route::middleware('auth.device')->controller(JuryTafelController::class)
    ->prefix('jurytafel')
    ->name('jurytafel.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{toestel}', 'toestel')->name('toestel');
    });

Route::post('auth/device/logout', [DeviceAuthController::class, 'logout'])->name('auth.device.logout');
