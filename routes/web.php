<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BankInfoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/tambah-bank', [\App\Http\Controllers\DashboardController::class, 'tambah_bank'])->name('dashboard.tambah-bank');
    Route::post('/tambah-bank/store', [\App\Http\Controllers\DashboardController::class, 'store_bank'])->name('dashboard.tambah-bank.store');
    Route::get('/get-token', [\App\Http\Controllers\DashboardController::class, 'get_token'])->name('dashboard.get-token');
    Route::post('/get-token/store', [\App\Http\Controllers\DashboardController::class, 'store_get_token'])->name('dashboard.get-token.store');
    Route::get('/create-va/{id_bank}', [\App\Http\Controllers\DashboardController::class, 'create_va'])->name('dashboard.create-va');
});

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('bank-info')->group(function(){
        Route::get('/', [BankInfoController::class, 'index'])->name('bank-info');
        Route::post('/', [BankInfoController::class, 'store'])->name('bank-info.store');
        Route::delete('/{bankInfo}', [BankInfoController::class, 'destroy'])->name('bank-info.destroy');
        Route::patch('/{bankInfo}', [BankInfoController::class, 'update'])->name('bank-info.update');
    });

    Route::prefix('tagihan')->group(function(){
        Route::get('/', [App\Http\Controllers\Tagihan\TagihanController::class, 'index'])->name('tagihan');
        Route::get('/get-tagihan/{semester}', [App\Http\Controllers\Tagihan\TagihanController::class, 'get_tagihan_unsri'])->name('tagihan.get-tagihan');
    });
});

require __DIR__.'/auth.php';
