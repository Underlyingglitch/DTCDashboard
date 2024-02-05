<?php

use App\Http\Controllers\JuryInputController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Request;


Route::controller(JuryInputController::class)->prefix('juryinput')->name('juryinput.')->group(function () {
    Route::get('/', 'index')->name('index');
});
