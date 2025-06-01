<?php

use App\Http\Controllers\InternalPersonController;
use App\Http\Controllers\KeyControlController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfileController;
use Spatie\Permission\Middleware\RoleMiddleware;
use App\Http\Controllers\PersonEntryController;

Route::middleware(['auth', RoleMiddleware::using(['porter', 'admin', 'rrhh'])])->group(function () {
    Route::get('/person-entries', [PersonEntryController::class, 'index'])->name('person-entries');

    Route::get('/internal-person', [InternalPersonController::class, 'index'])
        ->name('internal-person');

    Route::get('/person/{id}', [PersonController::class, 'show'])->name('person.show');
});

Route::middleware(['auth', RoleMiddleware::using(['porter', 'admin'])])->group(function () {
    Route::get('/packages', [PackageController::class, 'index'])->name('packages');

    Route::get('/key-control', [KeyControlController::class, 'index'])->name('key-control');
});

Route::middleware(['auth', RoleMiddleware::using(['admin', 'rrhh'])])->group(function () {
    Route::get('/internal-person/{id}', [InternalPersonController::class, 'show'])->name('internal-person.show');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');