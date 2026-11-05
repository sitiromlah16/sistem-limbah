<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MasterKategoriController;
use App\Http\Controllers\Admin\ValidasiLaporanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Manajer\ManajerDashboardController;
use App\Http\Controllers\Operator\LaporanController as OperatorLaporanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Pengelolaan Limbah dan Reject DTY
|--------------------------------------------------------------------------
*/

// Halaman Root: redirect ke login atau dashboard masing-masing role
Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'operator' => redirect()->route('operator.laporan.index'),
            'admin' => redirect()->route('admin.dashboard'),
            'manajer' => redirect()->route('manajer.dashboard'),
            default => abort(403, 'Role pengguna tidak dikenali.'),
        };
    }

    return redirect()->route('login');
});

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Modul OPERATOR PRODUKSI (Role: operator)
Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/laporan', [OperatorLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/create', [OperatorLaporanController::class, 'create'])->name('laporan.create');
    Route::post('/laporan', [OperatorLaporanController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/{id}/edit', [OperatorLaporanController::class, 'edit'])->name('laporan.edit');
    Route::put('/laporan/{id}', [OperatorLaporanController::class, 'update'])->name('laporan.update');
});

// Modul ADMIN PRODUKSI (Role: admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Master Data Kategori Waste & Reject (F-02)
    Route::get('/kategori', [MasterKategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [MasterKategoriController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{id}', [MasterKategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [MasterKategoriController::class, 'destroy'])->name('kategori.destroy');
    Route::post('/lanes', [MasterKategoriController::class, 'storeLane'])->name('lanes.store');
    Route::put('/lanes/{id}', [MasterKategoriController::class, 'updateLane'])->name('lanes.update');
    Route::delete('/lanes/{id}', [MasterKategoriController::class, 'destroyLane'])->name('lanes.destroy');

    // Validasi & Verifikasi Laporan Masuk (F-04)
    Route::get('/validasi', [ValidasiLaporanController::class, 'index'])->name('validasi.index');
    Route::get('/validasi/{id}', [ValidasiLaporanController::class, 'show'])->name('validasi.show');
    Route::post('/validasi/{id}/approve', [ValidasiLaporanController::class, 'validateLaporan'])->name('validasi.approve');
    Route::post('/validasi/{id}/reject', [ValidasiLaporanController::class, 'rejectLaporan'])->name('validasi.reject');
});

// Modul MANAJER PRODUKSI (Role: manajer)
Route::middleware(['auth', 'role:manajer'])->prefix('manajer')->name('manajer.')->group(function () {
    // Executive Dashboard (F-05)
    Route::get('/dashboard', [ManajerDashboardController::class, 'index'])->name('dashboard');

    // Laporan Periodik & Cetak (F-06)
    Route::get('/laporan', [ManajerDashboardController::class, 'laporan'])->name('laporan');
    Route::get('/cetak', [ManajerDashboardController::class, 'cetak'])->name('cetak');
});
