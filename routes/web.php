<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\DetailPenjualanController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('buku', BukuController::class);
Route::resource('distributor', DistributorController::class);

Route::resource('kasir', KasirController::class);
Route::patch('/kasir/{id_kasir}/toggle-status', [KasirController::class, 'toggleStatus'])->name('kasir.toggleStatus');

Route::resource('pembelian', \App\Http\Controllers\PembelianController::class);

Route::resource('penjualan', PenjualanController::class);