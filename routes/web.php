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

Route::middleware([])->group(base_path('routes/web.public.php'));

// non-authenticated users only
Route::middleware(['guest'])->group(base_path('routes/web.guest.php'));

// user routes
Route::middleware(['auth', 'verified'])->group(base_path('routes/web.auth.php'));
Route::middleware(['auth'])->group(base_path('routes/web.email.php'));
Route::middleware(['auth'])->get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
