<?php

use App\Http\Controllers\InternalPersonController;
use App\Http\Controllers\KeyControlController;
use App\Http\Controllers\KeyController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfileController;
use Spatie\Permission\Middleware\RoleMiddleware;
use App\Http\Controllers\PersonEntryController;

Route::middleware(['auth', RoleMiddleware::using(['porter', 'admin', 'rrhh'])])->group(function () {
    Route::get('/person-entries', [PersonEntryController::class, 'index'])->name('person-entries');
    Route::get('/person-entries/{id}', [PersonEntryController::class, 'show'])->name('person-entries.show');

    Route::get('/internal-person', [InternalPersonController::class, 'index'])
        ->name('internal-person');

    Route::get('/person', [PersonController::class, 'index'])->name('person.index');
    Route::get('/person/{id}', [PersonController::class, 'show'])->name('person.show');
});

Route::middleware(RoleMiddleware::using(['porter', 'admin']))->group(function () {
    Route::get('/packages', [PackageController::class, 'index'])->name('packages');
    Route::delete('/packages/{id}', [PackageController::class, 'destroy'])->name('packages.destroy');
    Route::get('/packages/deleted', [PackageController::class, 'deleted'])->name('packages.deleted');

    Route::get('/key-control/keys', [KeyController::class, 'index'])->name('keys.index');

    Route::get('/key-control', [KeyControlController::class, 'index'])->name('key-control');
    Route::get('/key-control/{id}', [KeyControlController::class, 'show'])->name('key-control.show');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');