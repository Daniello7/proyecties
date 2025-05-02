<?php

use App\Http\Controllers\Api\AlarmController;
use App\Http\Controllers\Api\AlarmGuardController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GuardController;
use App\Http\Controllers\Api\GuardReportController;
use App\Http\Controllers\Api\GuardZoneController;
use App\Http\Controllers\Api\ZoneController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,2');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('/guards', GuardController::class);
    Route::apiResource('/zones', ZoneController::class);
    Route::post('/guards/attach-zone', [GuardZoneController::class, 'attach']);
    Route::post('/guards/detach-zone', [GuardZoneController::class, 'detach']);

    Route::apiResource('/guard-reports', GuardReportController::class);
    Route::apiResource('/alarms', AlarmController::class);
    Route::post('/alarm/attach-guard', [AlarmGuardController::class, 'attach']);
});
