<?php

use App\Http\Controllers\Api\GuardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', RoleMiddleware::using('admin')])->group(function () {
    Route::apiResource('/guards', GuardController::class);
    Route::get('/guards?with_zones', [GuardController::class, 'index']);
    Route::get('/guards/{id}?with_zones', [GuardController::class, 'show']);
    Route::post('/guards/assign-zone', [GuardController::class, 'assignZone'])->name('guards.assignZone');
});
