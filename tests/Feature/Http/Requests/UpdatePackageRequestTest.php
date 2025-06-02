<?php

namespace Tests\Feature\Http\Requests\Package;

use App\Http\Requests\Package\UpdatePackageRequest;
use App\Models\InternalPerson;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::put('/test-package-update', function (UpdatePackageRequest $request) {
        return response()->json(['success' => true]);
    });
});

it('authorizes all users to make the request', function () {
    // Arrange
    $request = new UpdatePackageRequest();
    
    // Act & Assert
    expect($request->authorize())->toBeTrue();
});

it('passes validation when all fields are correct', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $data = [
        'agency' => 'Test Agency',
        'package_count' => 5,
        'external_entity' => 'External Company',
        'internal_person_id' => $internalPerson->id,
        'entry_time' => '2025-06-02 10:00:00',
        'exit_time' => '2025-06-02 11:00:00',
        'comment' => 'Test comment'
    ];

    // Act
    $response = $this->putJson('/test-package-update', $data);

    // Assert
    $response->assertStatus(200);
});

it('validates agency is required', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $data = [
        'package_count' => 5,
        'external_entity' => 'External Company',
        'internal_person_id' => $internalPerson->id,
        'entry_time' => '2025-06-02 10:00:00',
        'exit_time' => '2025-06-02 11:00:00',
    ];

    // Act
    $response = $this->putJson('/test-package-update', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['agency']);
});

it('validates package_count is required and integer', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $data = [
        'agency' => 'Test Agency',
        'package_count' => 'not-an-integer',
        'external_entity' => 'External Company',
        'internal_person_id' => $internalPerson->id,
        'entry_time' => '2025-06-02 10:00:00',
        'exit_time' => '2025-06-02 11:00:00',
    ];

    // Act
    $response = $this->putJson('/test-package-update', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['package_count']);
});

it('validates external_entity is required, string and max:255', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $data = [
        'agency' => 'Test Agency',
        'package_count' => 5,
        'external_entity' => str_repeat('a', 256),
        'internal_person_id' => $internalPerson->id,
        'entry_time' => '2025-06-02 10:00:00',
        'exit_time' => '2025-06-02 11:00:00',
    ];

    // Act
    $response = $this->putJson('/test-package-update', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['external_entity']);
});

it('validates internal_person_id exists', function () {
    // Arrange
    $data = [
        'agency' => 'Test Agency',
        'package_count' => 5,
        'external_entity' => 'External Company',
        'internal_person_id' => 99999,
        'entry_time' => '2025-06-02 10:00:00',
        'exit_time' => '2025-06-02 11:00:00',
    ];

    // Act
    $response = $this->putJson('/test-package-update', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['internal_person_id']);
});

it('validates entry_time is required and valid date', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $data = [
        'agency' => 'Test Agency',
        'package_count' => 5,
        'external_entity' => 'External Company',
        'internal_person_id' => $internalPerson->id,
        'entry_time' => 'not-a-date',
        'exit_time' => '2025-06-02 11:00:00',
    ];

    // Act
    $response = $this->putJson('/test-package-update', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['entry_time']);
});

it('validates exit_time is required and valid date', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $data = [
        'agency' => 'Test Agency',
        'package_count' => 5,
        'external_entity' => 'External Company',
        'internal_person_id' => $internalPerson->id,
        'entry_time' => '2025-06-02 10:00:00',
        'exit_time' => 'not-a-date',
    ];

    // Act
    $response = $this->putJson('/test-package-update', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['exit_time']);
});

it('validates comment is string and max:255 when provided', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $data = [
        'agency' => 'Test Agency',
        'package_count' => 5,
        'external_entity' => 'External Company',
        'internal_person_id' => $internalPerson->id,
        'entry_time' => '2025-06-02 10:00:00',
        'exit_time' => '2025-06-02 11:00:00',
        'comment' => str_repeat('a', 256)
    ];

    // Act
    $response = $this->putJson('/test-package-update', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['comment']);
});

it('allows null comment', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $data = [
        'agency' => 'Test Agency',
        'package_count' => 5,
        'external_entity' => 'External Company',
        'internal_person_id' => $internalPerson->id,
        'entry_time' => '2025-06-02 10:00:00',
        'exit_time' => '2025-06-02 11:00:00',
        'comment' => null
    ];

    // Act
    $response = $this->putJson('/test-package-update', $data);

    // Assert
    $response->assertStatus(200);
});

it('validates external_entity is string', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $data = [
        'agency' => 'Test Agency',
        'package_count' => 5,
        'external_entity' => ['not-a-string'],
        'internal_person_id' => $internalPerson->id,
        'entry_time' => '2025-06-02 10:00:00',
        'exit_time' => '2025-06-02 11:00:00',
    ];

    // Act
    $response = $this->putJson('/test-package-update', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['external_entity']);
});

it('validates comment is string when provided', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $data = [
        'agency' => 'Test Agency',
        'package_count' => 5,
        'external_entity' => 'External Company',
        'internal_person_id' => $internalPerson->id,
        'entry_time' => '2025-06-02 10:00:00',
        'exit_time' => '2025-06-02 11:00:00',
        'comment' => ['not-a-string']
    ];

    // Act
    $response = $this->putJson('/test-package-update', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['comment']);
});

it('fails when required fields are missing', function () {
    // Arrange
    $data = [];

    // Act
    $response = $this->putJson('/test-package-update', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'agency',
            'package_count',
            'external_entity',
            'internal_person_id',
            'entry_time',
            'exit_time'
        ]);
});

it('validates internal_person_id is required', function () {
    // Arrange
    $data = [
        'agency' => 'Test Agency',
        'package_count' => 5,
        'external_entity' => 'External Company',
        'entry_time' => '2025-06-02 10:00:00',
        'exit_time' => '2025-06-02 11:00:00',
    ];

    // Act
    $response = $this->putJson('/test-package-update', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['internal_person_id']);
});