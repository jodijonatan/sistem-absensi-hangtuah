<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    KelasController,
    SiswaController,
    AbsensiController,
    Auth\LoginController,
    Auth\LogoutController,
    SiswaDashboardController,
    SiswaAbsensiController
};

// 🏠 Public route - redirect ke login
Route::get('/', fn() => redirect()->route('login'));

// 🔐 Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// 🔒 Logout (untuk semua guard)
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// 👨‍🏫 Admin & Guru routes (guard: web)
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');

    // Kelas management
    Route::resource('kelas', KelasController::class)->parameters([
        'kelas' => 'kelas'
    ]);
    Route::get('/kelas/{kelas}/schedules', [KelasController::class, 'schedules'])->name('kelas.schedules');
    Route::post('/kelas/{kelas}/schedules', [KelasController::class, 'storeSchedule'])->name('kelas.schedules.store');
    Route::put('/kelas/{kelas}/schedules/{schedule}', [KelasController::class, 'updateSchedule'])->name('kelas.schedules.update');
    Route::delete('/kelas/{kelas}/schedules/{schedule}', [KelasController::class, 'destroySchedule'])->name('kelas.schedules.destroy');
    Route::get('/kelas/{kelas}/students', [KelasController::class, 'students'])->name('kelas.students');
    Route::get('/kelas/data/all', [KelasController::class, 'getData'])->name('kelas.data');

    // Siswa management
    Route::resource('siswa', SiswaController::class);
    Route::get('/siswa/export/data', [SiswaController::class, 'export'])->name('siswa.export');

    // Absensi management & reports
    Route::resource('absensi', AbsensiController::class);
    Route::get('/absensi-reports', [AbsensiController::class, 'reports'])->name('absensi.reports');
    Route::get('/absensi/student/{siswa}/detail', [AbsensiController::class, 'studentDetail'])->name('absensi.student-detail');
    Route::get('/absensi/export/data', [AbsensiController::class, 'export'])->name('absensi.export');
});

// 🎓 Siswa routes (guard: siswa)
Route::prefix('siswa')->middleware('auth:siswa')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('siswa.dashboard');
    Route::post('/absen', [SiswaAbsensiController::class, 'store'])->name('siswa.absen');
    Route::post('/logout', [LogoutController::class, 'logoutSiswa'])->name('siswa.logout');
});
