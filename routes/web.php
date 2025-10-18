<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\RfidApiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Models\Kelas;

// Public route - redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});

// Protected routes - require authentication
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');

    // Kelas (Classes) Management
    Route::resource('kelas', KelasController::class)->parameters([
        'kelas' => 'kelas'
    ]);
    Route::get('/kelas/{kelas}/schedules', [KelasController::class, 'schedules'])->name('kelas.schedules');
    Route::post('/kelas/{kelas}/schedules', [KelasController::class, 'storeSchedule'])->name('kelas.schedules.store');
    Route::put('/kelas/{kelas}/schedules/{schedule}', [KelasController::class, 'updateSchedule'])->name('kelas.schedules.update');
    Route::delete('/kelas/{kelas}/schedules/{schedule}', [KelasController::class, 'destroySchedule'])->name('kelas.schedules.destroy');
    Route::get('/kelas/{kelas}/students', [KelasController::class, 'students'])->name('kelas.students');
    Route::get('/kelas/data/all', [KelasController::class, 'getData'])->name('kelas.data');

    // Siswa (Students) Management
    Route::resource('siswa', SiswaController::class);
    Route::post('/siswa/{siswa}/assign-rfid', [SiswaController::class, 'assignRfid'])->name('siswa.assign-rfid');
    Route::delete('/siswa/{siswa}/remove-rfid', [SiswaController::class, 'removeRfid'])->name('siswa.remove-rfid');
    Route::get('/siswa/export/data', [SiswaController::class, 'export'])->name('siswa.export');

    // Absensi (Attendance) Management and Reports
    Route::resource('absensi', AbsensiController::class);
    Route::get('/absensi-reports', [AbsensiController::class, 'reports'])->name('absensi.reports');
    Route::get('/absensi/student/{siswa}/detail', [AbsensiController::class, 'studentDetail'])->name('absensi.student-detail');
    Route::get('/absensi/export/data', [AbsensiController::class, 'export'])->name('absensi.export');
});

// API Routes for RFID Reader (no authentication required for hardware access)
Route::prefix('api/rfid')->group(function () {
    Route::post('/tap', [RfidApiController::class, 'tapCard'])->name('api.rfid.tap');
    Route::get('/student/{uid}', [RfidApiController::class, 'getStudentByUid'])->name('api.rfid.student');
    Route::get('/test', [RfidApiController::class, 'test'])->name('api.rfid.test');
});
