<?php

use Spatie\Permission\Middleware\RoleMiddleware;

Route::middleware(['auth', RoleMiddleware::using('admin')])->group(function () {
    Route::get('/admin', fn() => view('admin'))->name('admin');
});
