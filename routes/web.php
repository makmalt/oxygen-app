<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataBotolController;
use App\Http\Controllers\DataPelangganController;
use App\Http\Controllers\DataPinjamanController;
use App\Http\Controllers\RiwayatPinjamanController;
use App\Http\Controllers\JenisBotolController;
use App\Models\DataBotol;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('data_botol', DataBotolController::class);
Route::resource('data_pelanggan', DataPelangganController::class);
Route::resource('data_pinjaman', DataPinjamanController::class);


Route::put('/data_botol/update-status/{id}', [DataBotolController::class, 'updateStatus'])->name('data_botol.updateStatus');

Route::get('form-pinjaman', function () {
    return view('form-pinjaman');
})->name('form-pinjaman');

Route::put('/data_pinjaman/update-status/{id}', [DataPinjamanController::class, 'updateStatus'])->name('data_pinjaman.updateStatus');
Route::get('/data_pinjaman/pelanggan-by-botol/{botolId}', [DataPinjamanController::class, 'getPelangganByBotol'])->name('data_pinjaman.pelangganByBotol');

// Jenis Botol API (minimal JSON for modal)
Route::get('/jenis_botol', [JenisBotolController::class, 'index'])->name('jenis_botol.index');
Route::post('/jenis_botol', [JenisBotolController::class, 'store'])->name('jenis_botol.store');
