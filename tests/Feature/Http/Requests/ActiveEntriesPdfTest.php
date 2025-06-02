<?php

use Illuminate\Support\Facades\Route;
use App\Http\Requests\ActiveEntriesPDF;

beforeEach(function () {
    Route::post('/test-route', function (ActiveEntriesPDF $request) {
        return response()->json(['success' => true]);
    });
});

it('authorizes all users to make the request', function () {
    // Arrange
    $request = new ActiveEntriesPDF();

    // Act & Assert
    expect($request->authorize())->toBeTrue();
});

it('validates request with valid data', function () {
    // Arrange
    $validData = [
        'columns' => json_encode(['id', 'name']),
        'entries' => json_encode([['id' => 1, 'name' => 'Test']])
    ];

    // Act
    $response = $this->postJson('/test-route', $validData);

    // Assert
    $response->assertStatus(200);
});

it('fails validation with missing columns', function () {
    // Arrange
    $invalidData = [
        'entries' => json_encode([['id' => 1]])
    ];

    // Act
    $response = $this->postJson('/test-route', $invalidData);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['columns']);
});

it('fails validation with missing entries', function () {
    // Arrange
    $invalidData = [
        'columns' => json_encode(['id'])
    ];

    // Act
    $response = $this->postJson('/test-route', $invalidData);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['entries']);
});

it('fails validation with invalid json in columns', function () {
    // Arrange
    $invalidData = [
        'columns' => 'invalid-json',
        'entries' => json_encode([])
    ];

    // Act
    $response = $this->postJson('/test-route', $invalidData);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['columns']);
});

it('fails validation with invalid json in entries', function () {
    // Arrange
    $invalidData = [
        'columns' => json_encode(['id']),
        'entries' => 'invalid-json'
    ];

    // Act
    $response = $this->postJson('/test-route', $invalidData);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['entries']);
});