<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SatelliteApiController;
use App\Http\Controllers\Api\GroundStationApiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API
Route::prefix('v1')->group(function () {
    Route::get('/satellites', [SatelliteApiController::class, 'index']);
    Route::get('/satellites/{id}', [SatelliteApiController::class, 'show']);
    Route::get('/ground-stations', [GroundStationApiController::class, 'index']);
    Route::get('/ground-stations/{id}', [GroundStationApiController::class, 'show']);
    Route::get('/stats', [SatelliteApiController::class, 'stats']);
});