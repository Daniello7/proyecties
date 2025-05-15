<?php

use App\Http\Controllers\ControlAccessController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PdfExportController;
use Spatie\Permission\Middleware\RoleMiddleware;

Route::middleware(['auth', RoleMiddleware::using(['porter'])])->group(function () {
    Route::get('/control-access', ControlAccessController::class)->name('control-access');

    Route::get('/driver-rules/{person}', [PDFController::class, 'driverRules'])->name('driver-rules');
    Route::get('/visitor-rules/{person}', [PDFController::class, 'visitorRules'])->name('visitor-rules');
    Route::get('/cleaning-rules/{person}', [PDFController::class, 'cleaningRules'])->name('cleaning-rules');
    Route::get('/pdf-exports/', [PdfExportController::class, 'index'])->name('pdf-exports');
});