<?php

use App\Http\Controllers\ControlAccessController;
use App\Http\Controllers\InternalPersonController;
use App\Http\Controllers\KeyControlController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PersonEntryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/control-access', ControlAccessController::class)->name('control-access');


Route::resource('/person-entries', PersonEntryController::class)
    ->names(['index' => 'person-entries'])
    ->parameters(['person-entries' => 'person-entry']);

Route::get('/person', [PersonController::class, 'index'])->name('person');
Route::get('/person/{id}', [PersonController::class, 'show'])->name('person.show');

Route::get('/internal-person', [InternalPersonController::class, 'index'])->name('internal-person');

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
