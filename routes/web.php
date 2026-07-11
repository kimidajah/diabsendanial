<?php

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\GuruController;

// Group Guru
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');
    Route::get('/history', [GuruController::class, 'history'])->name('history');
    Route::get('/leave', [GuruController::class, 'leave'])->name('leave');
    Route::post('/leave', [GuruController::class, 'storeLeave'])->name('leave.store');
});

use App\Http\Controllers\TuController;

// Group Tata Usaha (TU)
Route::middleware(['auth', 'role:tu'])->prefix('tu')->name('tu.')->group(function () {
    Route::get('/scanner', [TuController::class, 'scanner'])->name('scanner');
    Route::post('/scan', [TuController::class, 'scan'])->name('scan');
    Route::get('/reports', [TuController::class, 'reports'])->name('reports');
    Route::get('/export', [TuController::class, 'export'])->name('export');
});

use App\Http\Controllers\WakasekController;

// Group Wakasek Kurikulum
Route::middleware(['auth', 'role:wakasek'])->prefix('wakasek')->name('wakasek.')->group(function () {
    Route::get('/dashboard', [WakasekController::class, 'dashboard'])->name('dashboard');
    
    // CRUD Guru
    Route::get('/teachers', [WakasekController::class, 'teachers'])->name('teachers');
    Route::post('/teachers', [WakasekController::class, 'storeTeacher'])->name('teachers.store');
    Route::put('/teachers/{id}', [WakasekController::class, 'updateTeacher'])->name('teachers.update');
    Route::delete('/teachers/{id}', [WakasekController::class, 'destroyTeacher'])->name('teachers.destroy');
    
    // Settings & Holidays
    Route::get('/settings', [WakasekController::class, 'settings'])->name('settings');
    Route::post('/settings', [WakasekController::class, 'updateSettings'])->name('settings.update');
    Route::post('/holidays', [WakasekController::class, 'storeHoliday'])->name('holidays.store');
    Route::delete('/holidays/{id}', [WakasekController::class, 'destroyHoliday'])->name('holidays.destroy');
    
    // Leaves (Persetujuan Izin)
    Route::get('/leaves', [WakasekController::class, 'leaves'])->name('leaves');
    Route::post('/leaves/{id}/status', [WakasekController::class, 'updateLeaveStatus'])->name('leaves.status');
    
    // Reports
    Route::get('/reports', [WakasekController::class, 'reports'])->name('reports');
});
