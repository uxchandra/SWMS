<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\HakAksesController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('auth.login');
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
    Route::get('/api/department/', [ManajemenUserController::class, 'getDepartment']);
    Route::post('/data-pengguna/import', [ManajemenUserController::class, 'import'])->name('data-pengguna.import');
    Route::resource('/data-pengguna', ManajemenUserController::class);
    
    Route::get('/hak-akses/get-data', [HakAksesController::class, 'getDataRole']);
    Route::resource('/hak-akses', HakAksesController::class);

    Route::get('/barang/get-data', [BarangController::class, 'getDataBarang']);
    Route::post('/barang/import', [BarangController::class, 'import'])->name('barang.import');
    Route::resource('/barang', BarangController::class);
    
    Route::get('/jenis-barang/get-data', [JenisController::class, 'getDataJenisBarang']);
    Route::resource('/jenis-barang', JenisController::class);

    Route::get('/department/get-data', [DepartmentController::class, 'getDataDepartment']);
    Route::resource('/department', DepartmentController::class);


    Route::get('/barang/kode/{kode}', [BarangMasukController::class, 'getBarangByKode'])->name('barang.getByKode');

    Route::get('/barang/kode/{kode}', [BarangMasukController::class, 'getBarangByKode']);
    Route::get('/barang-masuk/get-data', [BarangMasukController::class, 'getDataBarangMasuk']);
    Route::get('/barang-masuk/{id}/detail', [BarangMasukController::class, 'detail'])->name('barang-masuk.detail');
    Route::resource('/barang-masuk', BarangMasukController::class);

    Route::resource('orders', OrderController::class)->middleware('auth');
    
});

require __DIR__.'/auth.php';
