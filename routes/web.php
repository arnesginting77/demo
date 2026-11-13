<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'form'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route(auth()->user()->role === 'siswa' ? 'ajukan' : 'dashboard'))->name('home');
    Route::get('/alat', [BarangController::class, 'index'])->name('alat');

    Route::middleware('role:admin,kabeng')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/monitoring', [LaporanController::class, 'monitoring'])->name('monitoring');
        Route::get('/laporan', [LaporanController::class, 'laporan'])->name('laporan');
    });

    Route::middleware('role:admin')->group(function () {
        Route::post('/alat', [BarangController::class, 'store'])->name('alat.store');
        Route::put('/alat/{barang}', [BarangController::class, 'update'])->name('alat.update');
        Route::delete('/alat/{barang}', [BarangController::class, 'destroy'])->name('alat.destroy');
        Route::post('/alat/{barang}/pulih', [BarangController::class, 'pulih'])->name('alat.pulih');

        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

        Route::get('/setuju', [PeminjamanController::class, 'setuju'])->name('setuju');
        Route::post('/setuju/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])->name('setuju.ya');
        Route::post('/setuju/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])->name('setuju.tolak');

        Route::get('/kembali', [PengembalianController::class, 'index'])->name('kembali');
        Route::post('/kembali/{peminjaman}', [PengembalianController::class, 'store'])->name('kembali.store');
    });

    Route::middleware('role:siswa')->group(function () {
        Route::get('/ajukan', [PeminjamanController::class, 'ajukan'])->name('ajukan');
        Route::post('/ajukan', [PeminjamanController::class, 'ajukanStore'])->name('ajukan.store');
    });
});
