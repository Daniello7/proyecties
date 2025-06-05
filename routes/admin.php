<?php

use App\Http\Controllers\CoverageController;
use Spatie\Permission\Middleware\RoleMiddleware;

Route::middleware(['auth', RoleMiddleware::using('admin')])->group(function () {
    Route::get('/admin', fn() => view('admin'))->name('admin');

    Route::get('/coverage/{path?}', CoverageController::class)
        ->name('coverage.report')
        ->where('path', '.*');
});
