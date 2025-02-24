<?php

use App\Http\Controllers\ControlAccessController;
use Illuminate\Support\Facades\Route;

it('loads the control access view', function () {
    // Arrange
    Route::get('/control-access', ControlAccessController::class);

    // Act
    $response = $this->get(route('control-access'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('control-access.index');
});
