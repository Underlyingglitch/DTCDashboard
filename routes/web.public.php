<?php

use App\Models\Device;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::get('/', function (Request $request) {
    if (Device::where('ip', $request->ip())->exists()) {
        return redirect()->route('jurytafel.index');
    }
    return view('index');
})->name('home');
