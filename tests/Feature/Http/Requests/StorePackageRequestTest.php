<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\Package\StorePackageRequest;
use App\Models\InternalPerson;
use App\Models\Person;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

beforeEach(function () {
    Route::post('/test-package', function (StorePackageRequest $request) {
        return response()->json(['success' => true]);
    });
});

it('authorizes all users to make the request', function () {
    // Arrange
    $request = new StorePackageRequest();

    // Act & Assert
    expect($request->authorize())->toBeTrue();
});

it('passes validation when all fields are correct', function () {
    // Arrange
    Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();
    $data = [
        'agency' => 'Agency 1',
        'external_entity' => 'Entity A',
        'internal_person_id' => $internalPerson->id,
        'package_count' => 5,
        'comment' => 'Package received',
    ];

    // Act
    $response = $this->postJson('/test-package', $data);

    // Assert
    $response->assertStatus(200);
});

it('validates agency is string', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $data = [
        'agency' => ['not-a-string'],
        'external_entity' => 'Entity A',
        'internal_person_id' => $internalPerson->id,
        'package_count' => 5,
        'comment' => 'Package received',
    ];

    // Act
    $response = $this->postJson('/test-package', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['agency']);
});

it('validates external_entity is string', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $data = [
        'agency' => 'Agency 1',
        'external_entity' => ['not-a-string'],
        'internal_person_id' => $internalPerson->id,
        'package_count' => 5,
        'comment' => 'Package received',
    ];

    // Act
    $response = $this->postJson('/test-package', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['external_entity']);
});

it('validates internal_person_id is integer', function () {
    // Arrange
    $data = [
        'agency' => 'Agency 1',
        'external_entity' => 'Entity A',
        'internal_person_id' => 'not-an-integer',
        'package_count' => 5,
        'comment' => 'Package received',
    ];

    // Act
    $response = $this->postJson('/test-package', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['internal_person_id']);
});

it('validates package_count is integer', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $data = [
        'agency' => 'Agency 1',
        'external_entity' => 'Entity A',
        'internal_person_id' => $internalPerson->id,
        'package_count' => 'not-an-integer',
        'comment' => 'Package received',
    ];

    // Act
    $response = $this->postJson('/test-package', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['package_count']);
});

it('validates comment is string when provided', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $data = [
        'agency' => 'Agency 1',
        'external_entity' => 'Entity A',
        'internal_person_id' => $internalPerson->id,
        'package_count' => 5,
        'comment' => ['not-a-string']
    ];

    // Act
    $response = $this->postJson('/test-package', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['comment']);
});

it('allows null comment', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $data = [
        'agency' => 'Agency 1',
        'external_entity' => 'Entity A',
        'internal_person_id' => $internalPerson->id,
        'package_count' => 5,
        'comment' => null
    ];

    // Act
    $response = $this->postJson('/test-package', $data);

    // Assert
    $response->assertStatus(200);
});

it('allows empty comment', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $data = [
        'agency' => 'Agency 1',
        'external_entity' => 'Entity A',
        'internal_person_id' => $internalPerson->id,
        'package_count' => 5,
        'comment' => ''
    ];

    // Act
    $response = $this->postJson('/test-package', $data);

    // Assert
    $response->assertStatus(200);
});

it('fails when required fields are missing', function () {
    // Arrange
    $data = [];

    // Act
    $response = $this->postJson('/test-package', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'agency',
            'external_entity',
            'internal_person_id',
            'package_count'
        ]);
});