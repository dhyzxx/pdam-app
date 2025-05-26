<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemakaianAirController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\DashboardController;
use App\Exports\PelangganExport;
use Maatwebsite\Excel\Facades\Excel;


Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Rute Pelanggan
Route::resource('pelanggan', PelangganController::class);

// Rute Pemakaian Air
Route::prefix('pelanggan/{pelanggan}/pemakaian-air')->name('pemakaian_air.')->group(function () {
    Route::get('/create', [PemakaianAirController::class, 'create'])->name('create');
    Route::post('/', [PemakaianAirController::class, 'store'])->name('store');
});
Route::get('pemakaian-air/{pemakaianAir}/edit', [PemakaianAirController::class, 'edit'])->name('pemakaian_air.edit');
Route::put('pemakaian-air/{pemakaianAir}', [PemakaianAirController::class, 'update'])->name('pemakaian_air.update');
Route::delete('pemakaian-air/{pemakaianAir}', [PemakaianAirController::class, 'destroy'])->name('pemakaian_air.destroy');


// Rute Tagihan dan Pembayaran
Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
Route::get('/tagihan/{tagihan}/bayar', [TagihanController::class, 'bayar'])->name('tagihan.bayar');
Route::post('/tagihan/{tagihan}/proses-bayar', [TagihanController::class, 'prosesBayar'])->name('tagihan.prosesBayar');
Route::get('/riwayat-pembayaran', [TagihanController::class, 'riwayatPembayaran'])->name('tagihan.riwayat');
Route::get('/tagihan/{tagihan}/cetak-pdf', [TagihanController::class, 'cetakTagihanPdf'])->name('tagihan.cetakPdf');
Route::get('/pembayaran/{pembayaran}/cetak-bukti-pdf', [TagihanController::class, 'cetakBuktiPembayaranPdf'])->name('pembayaran.cetakBuktiPdf');
Route::get('/tagihan/export-bulan/form', [TagihanController::class, 'formExportTagihanBulan'])->name('tagihan.formExportBulan');
Route::post('/tagihan/export-bulan/proses', [TagihanController::class, 'prosesExportTagihanBulan'])->name('tagihan.prosesExportBulan');

//export
Route::get('/pelanggan/export/', function () {
    return Excel::download(new PelangganExport, 'daftar_semua_pelanggan.xlsx');
})->name('pelanggan.export');