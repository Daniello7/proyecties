<?php

namespace Tests\Feature\Http\Requests\KeyControl;

use App\Http\Requests\KeyControl\UpdateKeyControlRequest;
use App\Models\Person;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::put('/test-key-control-update', function (UpdateKeyControlRequest $request) {
        return response()->json(['success' => true]);
    });
});

it('authorizes all users to make the request', function () {
    // Arrange
    $request = new UpdateKeyControlRequest();
    
    // Act & Assert
    expect($request->authorize())->toBeTrue();
});

it('passes validation when all fields are correct', function () {
    // Arrange
    $person = Person::factory()->create();
    $data = [
        'person_id' => $person->id,
        'comment' => 'Returning the key after use',
        'exit_time' => now()->subHour()->toDateTimeString(),
        'entry_time' => now()->toDateTimeString(),
    ];

    // Act
    $response = $this->putJson('/test-key-control-update', $data);

    // Assert
    $response->assertStatus(200);
});

it('fails validation when person_id is not an integer', function () {
    // Arrange
    $data = [
        'person_id' => 'not-an-integer',
        'comment' => 'Invalid person ID format',
        'exit_time' => now()->subHour()->toDateTimeString(),
        'entry_time' => now()->toDateTimeString(),
    ];

    // Act
    $response = $this->putJson('/test-key-control-update', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['person_id']);
});

it('fails validation when person_id does not exist', function () {
    // Arrange
    $data = [
        'person_id' => 99999,
        'comment' => 'Non-existent person',
        'exit_time' => now()->subHour()->toDateTimeString(),
        'entry_time' => now()->toDateTimeString(),
    ];

    // Act
    $response = $this->putJson('/test-key-control-update', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['person_id']);
});

it('validates comment is string when provided', function () {
    // Arrange
    $person = Person::factory()->create();
    $data = [
        'person_id' => $person->id,
        'comment' => ['not-a-string'],
        'exit_time' => now()->subHour()->toDateTimeString(),
        'entry_time' => now()->toDateTimeString(),
    ];

    // Act
    $response = $this->putJson('/test-key-control-update', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['comment']);
});

it('allows null comment', function () {
    // Arrange
    $person = Person::factory()->create();
    $data = [
        'person_id' => $person->id,
        'comment' => null,
        'exit_time' => now()->subHour()->toDateTimeString(),
        'entry_time' => now()->toDateTimeString(),
    ];

    // Act
    $response = $this->putJson('/test-key-control-update', $data);

    // Assert
    $response->assertStatus(200);
});

it('validates exit_time format', function () {
    // Arrange
    $person = Person::factory()->create();
    $data = [
        'person_id' => $person->id,
        'comment' => 'Test comment',
        'exit_time' => 'invalid-date-format',
        'entry_time' => now()->toDateTimeString(),
    ];

    // Act
    $response = $this->putJson('/test-key-control-update', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['exit_time']);
});

it('validates entry_time format', function () {
    // Arrange
    $person = Person::factory()->create();
    $data = [
        'person_id' => $person->id,
        'comment' => 'Test comment',
        'exit_time' => now()->subHour()->toDateTimeString(),
        'entry_time' => 'invalid-date-format',
    ];

    // Act
    $response = $this->putJson('/test-key-control-update', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['entry_time']);
});

it('accepts valid date formats for exit_time and entry_time', function () {
    // Arrange
    $person = Person::factory()->create();
    $data = [
        'person_id' => $person->id,
        'comment' => 'Test comment',
        'exit_time' => '2025-06-02 10:00:00',
        'entry_time' => '2025-06-02 11:00:00',
    ];

    // Act
    $response = $this->putJson('/test-key-control-update', $data);

    // Assert
    $response->assertStatus(200);
});

it('fails when required fields are missing', function () {
    // Arrange
    $data = [];

    // Act
    $response = $this->putJson('/test-key-control-update', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'person_id',
            'exit_time',
            'entry_time'
        ]);
});