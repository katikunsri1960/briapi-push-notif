<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dev', [\App\Http\Controllers\DevController::class, 'index'])->name('dev.index');
Route::post('/dev', [\App\Http\Controllers\DevController::class, 'store'])->name('dev.store');
