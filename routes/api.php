<?php

use App\Http\Controllers\Api\GuardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/guards', [GuardController::class, 'index']);
Route::post('/guards', [GuardController::class, 'store']);
Route::post('/guards/{guardId}/zones', [GuardController::class, 'assignZone']);
