<?php

use App\Http\Controllers\ControlAccessController;
use App\Http\Controllers\KeyControlController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PdfExportController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PersonEntryController;
use Spatie\Permission\Middleware\RoleMiddleware;

Route::middleware(['auth',RoleMiddleware::using(['porter'])])->group(function () {
    Route::get('/control-access', ControlAccessController::class)->name('control-access');

    Route::get('/driver-rules/{person}', [PDFController::class, 'driverRules'])->name('driver-rules');
    Route::get('/visitor-rules/{person}', [PDFController::class, 'visitorRules'])->name('visitor-rules');
    Route::get('/cleaning-rules/{person}', [PDFController::class, 'cleaningRules'])->name('cleaning-rules');
    Route::post('/active-entries-pdf/', [PDFController::class, 'activeEntriesPdf'])->name('active-entries.pdf');
    Route::get('/pdf-exports/', [PdfExportController::class, 'index'])->name('pdf-exports');

    Route::get('/person/create', [PersonController::class, 'create'])->name('person.create');
    Route::post('/person', [PersonController::class, 'store'])->name('person.store');
    Route::get('/person/{id}/edit', [PersonController::class, 'edit'])->name('person.edit');
    Route::put('/person/{id}', [PersonController::class, 'update'])->name('person.update');

    Route::get('/person-entries/create', [PersonEntryController::class, 'create'])->name('person-entries.create');
    Route::post('/person-entries', [PersonEntryController::class, 'store'])->name('person-entries.store');
    Route::get('/person-entries/edit/{id}', [PersonEntryController::class, 'edit'])->name('person-entries.edit');
    Route::put('/person-entries/{id}', [PersonEntryController::class, 'update'])->name('person-entries.update');

    Route::get('/packages/create/{type}', [PackageController::class, 'create'])->name('packages.create');
    Route::post('/packages/store/{type}', [PackageController::class, 'store'])->name('packages.store');
    Route::get('/packages/edit/{id}', [PackageController::class, 'edit'])->name('packages.edit');
    Route::put('/packages/{id}', [PackageController::class, 'update'])->name('packages.update');

    Route::get('/key-control/create', [KeyControlController::class, 'create'])->name('key-control.create');
    Route::post('/key-control', [KeyControlController::class, 'store'])->name('key-control.store');
    Route::get('/key-control/{id}/edit', [KeyControlController::class, 'edit'])->name('key-control.edit');
    Route::put('/key-control/{id}', [KeyControlController::class, 'update'])->name('key-control.update');
});