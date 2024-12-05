<?php

use App\Http\Controllers\InternalAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

if (config('app.env') === 'local' || config('app.env') === 'dev') {
    Route::post('/internal/ping', [InternalAPIController::class, 'ping'])->name('ping');
    Route::post('/internal/register', [InternalAPIController::class, 'register'])
        ->middleware(['web'])->name('register');
}

Route::middleware('internalapi')->controller(InternalAPIController::class)->prefix('/internal')->group(function () {
    Route::post('/changes', 'changes')->name('changes');
});
