<?php

use App\Http\Controllers\KeyControlController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PersonEntryController;
use Spatie\Permission\Middleware\RoleMiddleware;

Route::middleware(['auth', RoleMiddleware::using('admin')])->group(function () {
    Route::get('/admin', fn() => view('admin'))->name('admin');

    Route::delete('/person-entries/{id}', [PersonEntryController::class, 'destroy'])->name('person-entries.destroy');
    Route::delete('/person/{id}', [PersonController::class, 'destroy'])->name('person.destroy');
    Route::delete('/key-control/{id}', [KeyControlController::class, 'destroy'])->name('key-control.destroy');
    Route::delete('/packages/{id}', [PackageController::class, 'destroy'])->name('packages.destroy');
});
