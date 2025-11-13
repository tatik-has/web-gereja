<?php

use Illuminate\Support\Facades\Route;

// Kontroller Auth (Kita buat baru)
use App\Http\Controllers\Auth\LoginController;

// Kontroller Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JemaatController;
use App\Http\Controllers\Admin\PerhitunganController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\KeuanganController;
use App\Http\Controllers\Admin\AnggotaKeluargaController; // <-- Bagus, ini sudah benar

// Kontroller Jemaat
use App\Http\Controllers\Jemaat\JemaatDashboardController;
use App\Http\Controllers\Jemaat\PengajuanJemaatController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Awal
Route::get('/', function () {
    return redirect()->route('login');
});

// AUTENTIKASI (Satu Halaman Login)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// GRUP ADMIN (Dilindungi oleh guard 'admin')
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Jemaat (Admin mendaftarkan jemaat)
    Route::resource('jemaats', JemaatController::class);

    // Manajemen Pengajuan
    Route::get('pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('pengajuan/{id}', [PengajuanController::class, 'show'])->name('pengajuan.show');

    // Pengumuman
    Route::resource('pengumuman', PengumumanController::class)->except(['show']);

    // Keuangan
    Route::resource('keuangan', KeuanganController::class);

    Route::post('perhitungan/hitung', [PerhitunganController::class, 'hitung'])->name('perhitungan.hitung');
    Route::put('pengajuan/{id}/status', [PengajuanController::class, 'updateStatus'])->name('pengajuan.updateStatus');

    // Rute untuk mengelola anggota keluarga (Saya rapikan path-nya)
    Route::get('jemaat/{jemaat}/anggota', [AnggotaKeluargaController::class, 'index'])->name('anggota.index');
    Route::post('jemaat/{jemaat}/anggota', [AnggotaKeluargaController::class, 'store'])->name('anggota.store');
    Route::get('anggota/{anggota}/edit', [AnggotaKeluargaController::class, 'edit'])->name('anggota.edit');
    Route::put('anggota/{anggota}', [AnggotaKeluargaController::class, 'update'])->name('anggota.update');
    Route::delete('anggota/{anggota}', [AnggotaKeluargaController::class, 'destroy'])->name('anggota.destroy');

    Route::delete('/jemaats/{id}', [JemaatController::class, 'destroy'])->name('jemaats.destroy');
});


// GRUP JEMAAT (Dilindungi oleh guard 'web' / default)
Route::middleware(['auth', 'verified'])->prefix('jemaat')->name('jemaat.')->group(function () {

    Route::get('/dashboard', [JemaatDashboardController::class, 'index'])->name('dashboard');

    // Pengajuan Bantuan
    Route::get('/pengajuan/create', [PengajuanJemaatController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [PengajuanJemaatController::class, 'store'])->name('pengajuan.store');
    Route::get('/pengajuan', [PengajuanJemaatController::class, 'index'])->name('pengajuan.index'); // Melihat riwayat

    
    // Keuangan Gereja
    Route::get('/keuangan', [App\Http\Controllers\Jemaat\KeuanganController::class, 'index'])->name('keuangan');
});