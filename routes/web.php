<?php

use App\Http\Controllers\ControlAccessController;
use App\Http\Controllers\InternalPersonController;
use App\Http\Controllers\KeyControlController;
use App\Http\Controllers\KeyController;
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
    ->name('index', 'person-entries')
    ->parameters(['person-entries' => 'id']);

Route::resource('/person', PersonController::class)
    ->parameters(['person' => 'id']);

Route::get('/internal-person', [InternalPersonController::class, 'index'])->name('internal-person');

Route::get('/packages/create2', [PackageController::class, 'createExit'])->name('packages.createExit');
Route::resource('/packages', PackageController::class)
    ->name('index', 'packages')
    ->parameters(['packages' => 'id']);

Route::get('/key-control/keys', [KeyController::class, 'index'])->name('keys.index');

Route::resource('/key-control', KeyControlController::class)
    ->name('index', 'key-control')
    ->parameters(['key-control' => 'id']);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
