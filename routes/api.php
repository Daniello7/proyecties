<?php

use App\Http\Controllers\Api\GuardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/guards', GuardController::class);
Route::post('/guards/{guardId}/zones', [GuardController::class, 'assignZone']);
