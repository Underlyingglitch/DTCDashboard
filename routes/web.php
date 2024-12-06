<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/test', function () {
    return view('emails.verify');
});

Route::get('/config.js', function () {
    $config = 'window.__CONFIG__ = {
        VITE_REVERB_APP_KEY: "' . env('REVERB_APP_KEY') . '",
        VITE_REVERB_HOST: "' . env('REVERB_HOST') . '",
        VITE_REVERB_PORT: "' . env('REVERB_PORT') . '",
        VITE_REVERB_SCHEME: "' . env('REVERB_SCHEME') . '",
    };';

    return response($config, 200)
        ->header('Content-Type', 'application/javascript');
});

// non-authenticated users only
Route::middleware(['guest'])->group(base_path('routes/web.guest.php'));

// user routes
Route::middleware(['auth', 'verified', 'locked'])->group(base_path('routes/web.auth.php'));
Route::middleware(['auth'])->group(base_path('routes/web.email.php'));
Route::middleware(['auth'])->get('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

if (config('app.env') == 'local' || config('app.env') == 'dev') {
    Route::controller(AuthController::class)->name('auth.')->group(function () {
        Route::get('/auth/lock', 'lock')->name('lock')->middleware('auth');
        Route::get('/auth/login_as', 'login_as')->name('login_as');
    });
    Route::get('/auth/local', [AuthController::class, 'local'])->name('auth.local');
    Route::middleware(['auth', 'locked', 'jurytafel'])->group(base_path('routes/web.jurytafel.php'));
}
