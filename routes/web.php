<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\HakAksesController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/data-pengguna/get-data', [ManajemenUserController::class, 'getDataPengguna']);
        Route::get('/api/role/', [ManajemenUserController::class, 'getRole']);
        Route::resource('/data-pengguna', ManajemenUserController::class);
    
        Route::get('/hak-akses/get-data', [HakAksesController::class, 'getDataRole']);
        Route::resource('/hak-akses', HakAksesController::class);
});

require __DIR__.'/auth.php';
