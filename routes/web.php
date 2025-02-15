<?php

use App\Http\Controllers\ControlAccessController;
use App\Http\Controllers\InternalPersonController;
use App\Http\Controllers\KeyControlController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PersonEntryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/control-access', ControlAccessController::class)->name('control-access');

Route::get('/person-entries', [PersonEntryController::class, 'index'])->name('person-entries');
Route::get('/person-entries/create', [PersonEntryController::class, 'create'])->name('person-entries.create');
Route::post('/person-entries/store', [PersonEntryController::class, 'store'])->name('person-entries.store');
Route::get('/person-entries/{id}/edit', [PersonEntryController::class, 'edit'])->name('person-entries.edit');
Route::put('/person-entries/{id}', [PersonEntryController::class, 'update'])->name('person-entries.update');
Route::delete('/person-entries/{id}', [PersonEntryController::class, 'destroy'])->name('person-entries.destroy');

Route::get('/external-staff')->name('external-staff');

Route::get('/internal-staff', [InternalPersonController::class, 'index'])->name('internal-staff');

Route::get('/package', [PackageController::class, 'index'])->name('package');

Route::get('/key-control', [KeyControlController::class, 'index'])->name('key-control');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
