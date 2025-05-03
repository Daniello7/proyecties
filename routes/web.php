<?php

use App\Http\Controllers\ControlAccessController;
use App\Http\Controllers\InternalPersonController;
use App\Http\Controllers\KeyControlController;
use App\Http\Controllers\KeyController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PersonEntryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/admin', fn() => view('admin'))->name('admin');

Route::middleware(RoleMiddleware::using(['porter', 'admin', 'rrhh']))->group(function () {
    Route::resource('/person-entries', PersonEntryController::class)
        ->name('index', 'person-entries')
        ->parameters(['person-entries' => 'id']);

    Route::get('/internal-person', [InternalPersonController::class, 'index'])
        ->name('internal-person');

    Route::resource('/person', PersonController::class)
        ->parameters(['person' => 'id']);
});

Route::middleware(RoleMiddleware::using(['porter']))->group(function () {
    Route::get('/driver-rules/{person}', [PDFController::class, 'driverRules'])->name('driver-rules');
    Route::get('/visitor-rules/{person}', [PDFController::class, 'visitorRules'])->name('visitor-rules');
    Route::get('/cleaning-rules/{person}', [PDFController::class, 'cleaningRules'])->name('cleaning-rules');
});

Route::middleware(RoleMiddleware::using(['porter', 'admin']))->group(function () {
    Route::get('/control-access', ControlAccessController::class)->name('control-access');

    Route::get('/packages/create/{type}', [PackageController::class, 'create'])->name('packages.create');
    Route::get('/packages/edit/{id}', [PackageController::class, 'edit'])->name('packages.edit');
    Route::post('/packages/store/{type}', [PackageController::class, 'store'])->name('packages.store');
    Route::resource('/packages', PackageController::class)
        ->name('index', 'packages')
        ->parameters(['packages' => 'id'])
        ->except(['create', 'store', 'edit']);

    Route::get('/key-control/keys', [KeyController::class, 'index'])->name('keys.index');

    Route::resource('/key-control', KeyControlController::class)
        ->name('index', 'key-control')
        ->parameters(['key-control' => 'id']);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
