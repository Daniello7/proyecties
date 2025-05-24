<?php

use App\Http\Controllers\HumanResourcesController;
use Spatie\Permission\Middleware\RoleMiddleware;

Route::middleware(['auth', RoleMiddleware::using('rrhh')])->group(function () {
    Route::get('/human-resources', HumanResourcesController::class)->name('human-resources');
});