<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataBotolController;
use App\Http\Controllers\DataPelangganController;
use App\Http\Controllers\DataPinjamanController;
use App\Http\Controllers\RiwayatPinjamanController;
use App\Http\Controllers\JenisBotolController;
use App\Http\Controllers\TransaksiIsiBotolController;
use App\Http\Controllers\DataSupplierController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('data_botol', DataBotolController::class);
Route::resource('data_pelanggan', DataPelangganController::class);
// IMPORTANT: place data route BEFORE resource to prevent collision with {data_pinjaman}
Route::get('/data_pinjaman/data', [DataPinjamanController::class, 'data'])->name('data_pinjaman.data');
Route::resource('data_pinjaman', DataPinjamanController::class);
Route::get('/data_pinjaman/data', [DataPinjamanController::class, 'data'])->name('data_pinjaman.data');


Route::put('/data_botol/update-status/{id}', [DataBotolController::class, 'updateStatus'])->name('data_botol.updateStatus');

Route::get('form-pinjaman', function () {
    return view('form-pinjaman');
})->name('form-pinjaman');

Route::put('/data_pinjaman/update-status/{id}', [DataPinjamanController::class, 'updateStatus'])->name('data_pinjaman.updateStatus');
Route::get('/data_pinjaman/pelanggan-by-botol/{botolId}', [DataPinjamanController::class, 'getPelangganByBotol'])->name('data_pinjaman.pelangganByBotol');

// Jenis Botol API (minimal JSON for modal)
Route::get('/jenis_botol', [JenisBotolController::class, 'index'])->name('jenis_botol.index');
Route::post('/jenis_botol', [JenisBotolController::class, 'store'])->name('jenis_botol.store');

// Transaksi isi botol
Route::get('/transaksi-isi-botol', [TransaksiIsiBotolController::class, 'index'])->name('transaksi_isi_botol.index');
Route::get('/transaksi-isi-botol/data', [TransaksiIsiBotolController::class, 'data'])->name('transaksi_isi_botol.data');
Route::get('/transaksi-isi-botol/create', [TransaksiIsiBotolController::class, 'create'])->name('transaksi_isi_botol.create');
Route::post('/transaksi-isi-botol', [TransaksiIsiBotolController::class, 'store'])->name('transaksi_isi_botol.store');
Route::get('/transaksi-isi-botol/crosscheck/{id}', [TransaksiIsiBotolController::class, 'crosscheck'])->name('transaksi_isi_botol.crosscheck');
Route::post('/transaksi-isi-botol/crosscheck/{id}', [TransaksiIsiBotolController::class, 'crosscheckStore'])->name('transaksi_isi_botol.crosscheck.store');

// Supplier
Route::resource('data_supplier', DataSupplierController::class)->only(['index', 'create', 'store']);
