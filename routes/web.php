<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Dokter\DashboardController as DokterDashboardController;
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Dokter\PeriksaPasienController;
use App\Http\Controllers\Dokter\RiwayatPasienController;
use App\Http\Controllers\Pasien\DashboardController as PasienDashboardController;
use App\Http\Controllers\Pasien\DaftarPoliController;
use App\Http\Controllers\Pasien\RiwayatPendaftaranController;
use App\Http\Controllers\Pasien\PembayaranController as PasienPembayaranController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('polis', PoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);

    // Export Excel
    Route::get('/export/dokter', [ExportController::class, 'dokter'])->name('admin.export.dokter');
    Route::get('/export/pasien', [ExportController::class, 'pasien'])->name('admin.export.pasien');
    Route::get('/export/obat', [ExportController::class, 'obat'])->name('admin.export.obat');

    // Pembayaran verification
    Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('admin.pembayaran.index');
    Route::get('/pembayaran/{id}', [AdminPembayaranController::class, 'show'])->name('admin.pembayaran.show');
    Route::post('/pembayaran/{id}/confirm', [AdminPembayaranController::class, 'confirm'])->name('admin.pembayaran.confirm');
});

Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', [DokterDashboardController::class, 'index'])->name('dokter.dashboard');

    // Jadwal Periksa
    Route::get('/jadwal-periksa', [JadwalPeriksaController::class, 'index'])->name('dokter.jadwal-periksa.index');
    Route::get('/jadwal-periksa/create', [JadwalPeriksaController::class, 'create'])->name('dokter.jadwal-periksa.create');
    Route::post('/jadwal-periksa', [JadwalPeriksaController::class, 'store'])->name('dokter.jadwal-periksa.store');
    Route::get('/jadwal-periksa/{id}/edit', [JadwalPeriksaController::class, 'edit'])->name('dokter.jadwal-periksa.edit');
    Route::put('/jadwal-periksa/{id}', [JadwalPeriksaController::class, 'update'])->name('dokter.jadwal-periksa.update');
    Route::delete('/jadwal-periksa/{id}', [JadwalPeriksaController::class, 'destroy'])->name('dokter.jadwal-periksa.destroy');

    // Periksa Pasien
    Route::get('/periksa-pasien', [PeriksaPasienController::class, 'index'])->name('dokter.periksa-pasien.index');
    Route::get('/periksa-pasien/{id}', [PeriksaPasienController::class, 'show'])->name('dokter.periksa-pasien.show');
    Route::post('/periksa-pasien/{id}', [PeriksaPasienController::class, 'store'])->name('dokter.periksa-pasien.store');

    // Riwayat Pasien
    Route::get('/riwayat-pasien', [RiwayatPasienController::class, 'index'])->name('dokter.riwayat-pasien.index');
    Route::get('/riwayat-pasien/{id}', [RiwayatPasienController::class, 'show'])->name('dokter.riwayat-pasien.show');

    // Export Excel
    Route::get('/export/jadwal-periksa', [ExportController::class, 'jadwalPeriksa'])->name('dokter.export.jadwal');
    Route::get('/export/riwayat-pasien', [ExportController::class, 'riwayatPasien'])->name('dokter.export.riwayat');
});

Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', [PasienDashboardController::class, 'index'])->name('pasien.dashboard');

    // Daftar Poli
    Route::get('/daftar', [DaftarPoliController::class, 'create'])->name('pasien.daftar.create');
    Route::post('/daftar', [DaftarPoliController::class, 'store'])->name('pasien.daftar.store');
    Route::get('/daftar/jadwal/{poli}', [DaftarPoliController::class, 'getJadwal'])->name('pasien.daftar.jadwal');

    // Riwayat Pendaftaran
    Route::get('/riwayat', [RiwayatPendaftaranController::class, 'index'])->name('pasien.riwayat.index');
    Route::get('/riwayat/{id}', [RiwayatPendaftaranController::class, 'show'])->name('pasien.riwayat.show');

    // Pembayaran
    Route::get('/pembayaran', [PasienPembayaranController::class, 'index'])->name('pasien.pembayaran.index');
    Route::post('/pembayaran/{id}/upload', [PasienPembayaranController::class, 'upload'])->name('pasien.pembayaran.upload');
});