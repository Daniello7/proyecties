<?php

use App\Http\Controllers\InternalPersonController;
use Illuminate\Support\Facades\Route;

it('loads the internal person index view', function () {
    // Arrange
    Route::get('/internal-person', [InternalPersonController::class, 'index']);

    // Act
    $response = $this->get(route('internal-person'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('internal-person.index');
});
