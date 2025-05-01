<?php

use App\Http\Controllers\Api\AlarmController;
use App\Http\Controllers\Api\GuardController;
use App\Http\Controllers\Api\GuardReportController;
use App\Http\Controllers\Api\ZoneController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/guards', GuardController::class);
    Route::apiResource('/zones', ZoneController::class);
    Route::post('/guards/attach-zone', [GuardController::class, 'attachZone']);
    Route::post('/guards/detach-zone', [GuardController::class, 'detachZone']);
    Route::apiResource('/alarms', AlarmController::class);
    Route::apiResource('/guard-reports', GuardReportController::class);
});
