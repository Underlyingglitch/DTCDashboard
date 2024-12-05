<?php

use App\Http\Controllers\JuryTafelController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Request;


Route::controller(JuryTafelController::class)
    ->prefix('jurytafel')
    ->name('jurytafel.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{toestel}', 'toestel')->name('toestel');
    });
