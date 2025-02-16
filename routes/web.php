<?php

use App\Http\Controllers\AdminDashboard;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\KasirDashboard;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MemberBarang;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PembelianDetailController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\Riwayat;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;


Route::get('/login', function () {
    return view('auth.login.index');
})->middleware('guest');
Route::get('/', function () {
    return view('auth.login.index');
});



Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/pembelian/export-pdf', [ExportController::class, 'exportPdf'])->name('exportPdf.pembelian');

Route::middleware(['auth'])->group(function () {

    // Grup untuk Admin
    Route::middleware(['role:admin,pimpinan'])->group(function () {
        Route::get('/dashboard/admin',[AdminDashboard::class, 'index'])->name('dashboard.admin');
        Route::get('/dashboard/pimpinan',[AdminDashboard::class, 'index'])->name('dashboard.pimpinan');
        Route::resource('barang', BarangController::class);
        Route::resource('kategori', KategoriController::class);
        Route::resource('member', MemberController::class);
        Route::resource('pembelian', PembelianController::class);
        Route::resource('pembelian-detail', PembelianDetailController::class);
        Route::resource('penjualan', PenjualanController::class);
        Route::resource('supplier', SupplierController::class);
       
       
    });

    // Grup untuk Kasir
    Route::middleware(['role:kasir'])->group(function () {
        Route::get('/dashboard/kasir',[KasirDashboard::class, 'index'])->name('dashboard.kasir');
       
        Route::resource('penjualan', PenjualanController::class);
        Route::resource('member', MemberController::class);

    });

    // Grup untuk Pemimpin
    // Route::middleware(['role:pimpinan'])->group(function () {
    //     Route::get('/dashboard/pimpinan', function () {
    //         return view('admin.dashboard.index');
    //     })->name('dashboard.pimpinan');

    // });

    Route::middleware(['role:member'])->group(function () {   
    Route::get('/riwayat', [Riwayat::class, 'index'])->name('riwayat.member');
    Route::get('/dashboard/member', [MemberBarang::class, 'index'])->name('dashboard.member');
    });



});

