<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PelatihController;
use App\Http\Controllers\Admin\KontingenController;
use App\Http\Controllers\Admin\PesertaController;
use App\Http\Controllers\Admin\DokumenPesertaController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\KategoriLombaController;
use App\Http\Controllers\Admin\SubkategoriLombaController;
use App\Http\Controllers\Admin\KelompokUsiaController;
use App\Http\Controllers\Admin\KelasTandingController;
use App\Http\Controllers\Admin\PertandinganController;
use App\Http\Controllers\Admin\JadwalPertandinganController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\AdminLogController;

Route::get('/', function () {
    return redirect()->route('admin.login.form');
});

// Guest routes for admin
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login.form')->middleware('redirectIfAdminAuthenticated');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login');
});

// Protected admin routes
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Pelatih Management
    Route::get('pelatih', [PelatihController::class, 'index'])->name('admin.pelatih.index');
    Route::get('pelatih/{pelatih}', [PelatihController::class, 'show'])->name('admin.pelatih.show');
    Route::put('pelatih/{pelatih}/reset-password', [PelatihController::class, 'resetPassword'])->name('admin.pelatih.reset-password');
    Route::put('pelatih/{pelatih}/toggle-status', [PelatihController::class, 'toggleStatus'])->name('admin.pelatih.toggle-status');
    
    // Kontingen Management
    Route::get('kontingen', [KontingenController::class, 'index'])->name('admin.kontingen.index');
    Route::get('kontingen/{kontingen}', [KontingenController::class, 'show'])->name('admin.kontingen.show');
    Route::put('kontingen/{kontingen}/toggle-status', [KontingenController::class, 'toggleStatus'])->name('admin.kontingen.toggle-status');
    
    // Peserta Management
    Route::get('peserta', [PesertaController::class, 'index'])->name('admin.peserta.index');
    Route::put('peserta/{peserta}/verify', [PesertaController::class, 'verify'])->name('admin.peserta.verify');
    Route::put('peserta/{peserta}/override-class', [PesertaController::class, 'overrideClass'])->name('admin.peserta.override-class');
    
    // Dokumen Management
    Route::get('dokumen', [DokumenPesertaController::class, 'index'])->name('admin.dokumen.index');
    Route::get('dokumen/{dokumen}/download', [DokumenPesertaController::class, 'download'])->name('admin.dokumen.download');
    Route::put('dokumen/{dokumen}/verify', [DokumenPesertaController::class, 'verify'])->name('admin.dokumen.verify');
    
    // Pembayaran Management
    Route::get('pembayaran', [PembayaranController::class, 'index'])->name('admin.pembayaran.index');
    Route::put('pembayaran/{pembayaran}/verify', [PembayaranController::class, 'verifyPayment'])->name('admin.pembayaran.verify');
    
    // Kategori & Subkategori Management
    Route::resource('kategori-lomba', KategoriLombaController::class)->names([
        'index' => 'admin.kategori-lomba.index',
        'create' => 'admin.kategori-lomba.create',
        'store' => 'admin.kategori-lomba.store',
        'show' => 'admin.kategori-lomba.show',
        'edit' => 'admin.kategori-lomba.edit',
        'update' => 'admin.kategori-lomba.update',
        'destroy' => 'admin.kategori-lomba.destroy',
    ]);
    
    Route::resource('subkategori-lomba', SubkategoriLombaController::class)->names([
        'index' => 'admin.subkategori-lomba.index',
        'create' => 'admin.subkategori-lomba.create',
        'store' => 'admin.subkategori-lomba.store',
        'show' => 'admin.subkategori-lomba.show',
        'edit' => 'admin.subkategori-lomba.edit',
        'update' => 'admin.subkategori-lomba.update',
        'destroy' => 'admin.subkategori-lomba.destroy',
    ]);
    
    // Kelompok Usia & Kelas Tanding
    Route::resource('kelompok-usia', KelompokUsiaController::class)->names([
        'index' => 'admin.kelompok-usia.index',
        'create' => 'admin.kelompok-usia.create',
        'store' => 'admin.kelompok-usia.store',
        'show' => 'admin.kelompok-usia.show',
        'edit' => 'admin.kelompok-usia.edit',
        'update' => 'admin.kelompok-usia.update',
        'destroy' => 'admin.kelompok-usia.destroy',
    ]);
    
    Route::resource('kelas-tanding', KelasTandingController::class)->names([
        'index' => 'admin.kelas-tanding.index',
        'create' => 'admin.kelas-tanding.create',
        'store' => 'admin.kelas-tanding.store',
        'show' => 'admin.kelas-tanding.show',
        'edit' => 'admin.kelas-tanding.edit',
        'update' => 'admin.kelas-tanding.update',
        'destroy' => 'admin.kelas-tanding.destroy',
    ]);
    
    // Pertandingan & Jadwal
    Route::resource('pertandingan', PertandinganController::class)->names([
        'index' => 'admin.pertandingan.index',
        'create' => 'admin.pertandingan.create',
        'store' => 'admin.pertandingan.store',
        'show' => 'admin.pertandingan.show',
        'edit' => 'admin.pertandingan.edit',
        'update' => 'admin.pertandingan.update',
        'destroy' => 'admin.pertandingan.destroy',
    ]);
    
    Route::resource('jadwal-pertandingan', JadwalPertandinganController::class)->names([
        'index' => 'admin.jadwal-pertandingan.index',
        'create' => 'admin.jadwal-pertandingan.create',
        'store' => 'admin.jadwal-pertandingan.store',
        'show' => 'admin.jadwal-pertandingan.show',
        'edit' => 'admin.jadwal-pertandingan.edit',
        'update' => 'admin.jadwal-pertandingan.update',
        'destroy' => 'admin.jadwal-pertandingan.destroy',
    ]);
    
    // Laporan
    Route::get('laporan/peserta', [LaporanController::class, 'peserta'])->name('admin.laporan.peserta');
    Route::get('laporan/pembayaran', [LaporanController::class, 'pembayaran'])->name('admin.laporan.pembayaran');
    Route::get('laporan/export-peserta', [LaporanController::class, 'exportPeserta'])->name('admin.laporan.export-peserta');
    Route::get('laporan/export-pembayaran', [LaporanController::class, 'exportPembayaran'])->name('admin.laporan.export-pembayaran');
    
    // Admin Logs
    Route::get('logs', [AdminLogController::class, 'index'])->name('admin.logs.index');
    Route::get('logs/{adminLog}', [AdminLogController::class, 'show'])->name('admin.logs.show');
});