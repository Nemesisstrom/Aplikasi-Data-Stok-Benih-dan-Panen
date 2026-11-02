<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StokBenihController;
use App\Http\Controllers\VarietasController;
use App\Http\Controllers\CatatanPanenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

// -------------------------------------------------------------
// 1. Redirect Root URL (Halaman Utama)
// -------------------------------------------------------------
Route::get('/', function () {
    return redirect()->route('login');
});

// -------------------------------------------------------------
// 2. Guest Routes (Hanya untuk pengguna yang BELUM login)
// -------------------------------------------------------------
Route::middleware(['guest'])->group(function () {
    // Form & Proses Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Form & Proses Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// -------------------------------------------------------------
// 3. Auth Routes (Hanya untuk pengguna yang SUDAH login)
// -------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Utama & Ekspor PDF Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/export-pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.export-pdf');

    // Resource Data Master & Operasional
    Route::resource('varietas', VarietasController::class)->except(['create', 'edit', 'show']);
    Route::resource('stok-benih', StokBenihController::class);
    Route::resource('catatan-panen', CatatanPanenController::class);

    // Route Khusus Admin Pusat (User & Role Management)
    Route::middleware(['role:admin_pusat'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class)->except(['show']);
    });

    // Route Ekspor Laporan Excel & PDF
    Route::middleware(['permission:export excel|export pdf'])->group(function () {
        Route::get('/stok-benih/export/excel', [LaporanController::class, 'exportStokExcel'])->name('stok.export.excel');
        Route::get('/stok-benih/export/pdf', [LaporanController::class, 'exportStokPdf'])->name('stok.export.pdf');

        Route::get('/catatan-panen/export/excel', [LaporanController::class, 'exportPanenExcel'])->name('panen.export.excel');
        Route::get('/catatan-panen/export/pdf', [LaporanController::class, 'exportPanenPdf'])->name('panen.export.pdf');
    });
});
