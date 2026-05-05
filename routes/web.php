<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SatelliteController;
use App\Http\Controllers\GroundStationController;
use App\Http\Controllers\ActivityLogController;

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Satellite Routes
    Route::resource('satellites', SatelliteController::class);
    Route::get('/satellites/export/excel', [SatelliteController::class, 'exportExcel'])->name('satellites.export.excel');
    Route::get('/satellites/export/pdf', [SatelliteController::class, 'exportPdf'])->name('satellites.export.pdf');

    // Ground Station Routes
    Route::resource('ground-stations', GroundStationController::class);
    Route::get('/ground-stations/export/excel', [GroundStationController::class, 'exportExcel'])->name('ground-stations.export.excel');
    Route::get('/ground-stations/export/pdf', [GroundStationController::class, 'exportPdf'])->name('ground-stations.export.pdf');

    // Activity Log
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index')->middleware('admin');
});