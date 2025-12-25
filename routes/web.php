<?php

use Illuminate\Support\Facades\Route;

// Kontroller Auth
use App\Http\Controllers\Auth\LoginController;

// Kontroller Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JemaatController;
use App\Http\Controllers\Admin\PerhitunganController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\KeuanganController;
use App\Http\Controllers\Admin\AnggotaKeluargaController; 
use App\Http\Controllers\Admin\PendetaController;

// Kontroller Jemaat
use App\Http\Controllers\Jemaat\JemaatDashboardController;
use App\Http\Controllers\Jemaat\PengajuanJemaatController;
use App\Http\Controllers\Jemaat\KeuanganController as JemaatKeuanganController; // FIX: Perbaiki agar tidak konflik dengan Admin\KeuanganController

// ===================================================================
// Kontroller PENDETA (Read-Only) - DITAMBAHKAN DENGAN ALIAS YANG BENAR
// ===================================================================
use App\Http\Controllers\Pendeta\PendetaDashboardController;
use App\Http\Controllers\Pendeta\JemaatController as PendetaJemaatController;
use App\Http\Controllers\Pendeta\PengajuanController as PendetaPengajuanController;
use App\Http\Controllers\Pendeta\KeuanganController as PendetaKeuanganController;


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


// GRUP ADMIN (Dilindungi oleh guard 'admin' dan role 'admin')
// Saya tambahkan middleware role:admin untuk memastikan hanya Admin yang bisa CRUD
Route::middleware(['auth:admin', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Jemaat 
    Route::resource('jemaats', JemaatController::class);

    // CRUD Pendeta
    Route::resource('pendeta', PendetaController::class);

    // Manajemen Pengajuan
    Route::get('pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('pengajuan/{id}', [PengajuanController::class, 'show'])->name('pengajuan.show');
    Route::put('pengajuan/{id}/status', [PengajuanController::class, 'updateStatus'])->name('pengajuan.updateStatus');

    // Pengumuman
    Route::resource('pengumuman', PengumumanController::class)->except(['show']);

    // Keuangan
    Route::resource('keuangan', KeuanganController::class);

    Route::post('perhitungan/hitung', [PerhitunganController::class, 'hitung'])->name('perhitungan.hitung');

    // Rute untuk mengelola anggota keluarga
    Route::get('jemaat/{jemaat}/anggota', [AnggotaKeluargaController::class, 'index'])->name('anggota.index');
    Route::post('jemaat/{jemaat}/anggota', [AnggotaKeluargaController::class, 'store'])->name('anggota.store');
    Route::get('anggota/{anggota}/edit', [AnggotaKeluargaController::class, 'edit'])->name('anggota.edit');
    Route::put('anggota/{anggota}', [AnggotaKeluargaController::class, 'update'])->name('anggota.update');
    Route::delete('anggota/{anggota}', [AnggotaKeluargaController::class, 'destroy'])->name('anggota.destroy');

    Route::delete('/jemaats/{id}', [JemaatController::class, 'destroy'])->name('jemaats.destroy');
});

// ===================================================================
// GRUP PENDETA (READ-ONLY)
// ===================================================================
Route::middleware(['auth:admin', 'role:pendeta'])->prefix('pendeta')->name('pendeta.')->group(function () {

    Route::get('/dashboard', [PendetaDashboardController::class, 'index'])->name('dashboard');

    // Akses Read-Only untuk Jemaat (MENGGUNAKAN ALIAS)
    Route::get('/jemaat', [PendetaJemaatController::class, 'index'])->name('jemaat.index');
    Route::get('/jemaat/{id}', [PendetaJemaatController::class, 'show'])->name('jemaat.show');

    // Akses Read-Only untuk Pengajuan (Melihat saja)
    Route::get('/pengajuan', [PendetaPengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/{id}', [PendetaPengajuanController::class, 'show'])->name('pengajuan.show');

    // Akses Read-Only untuk Keuangan (Melihat saja)
    Route::get('/keuangan', [PendetaKeuanganController::class, 'index'])->name('keuangan.index');

    // **TIDAK ADA Rute CREATE, STORE, EDIT, UPDATE, DELETE**
});

// ===================================================================
// GRUP JEMAAT
// ===================================================================
Route::middleware(['auth', 'verified'])->prefix('jemaat')->name('jemaat.')->group(function () {

    Route::get('/dashboard', [JemaatDashboardController::class, 'index'])->name('dashboard');

    // Pengajuan Bantuan
    Route::get('/pengajuan/create', [PengajuanJemaatController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [PengajuanJemaatController::class, 'store'])->name('pengajuan.store');
    Route::get('/pengajuan', [PengajuanJemaatController::class, 'index'])->name('pengajuan.index'); // Melihat riwayat

    // Keuangan Gereja
    // Menggunakan alias JemaatKeuanganController yang sudah diperbaiki di atas
    Route::get('/keuangan', [JemaatKeuanganController::class, 'index'])->name('keuangan'); 
});