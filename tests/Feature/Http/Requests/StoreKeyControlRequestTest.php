<?php

namespace Tests\Feature\Http\Requests\KeyControl;

use App\Models\Key;
use App\Models\Person;
use App\Http\Requests\KeyControl\StoreKeyControlRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

beforeEach(function () {
    Route::post('/test-key-control', function (StoreKeyControlRequest $request) {
        return response()->json(['success' => true]);
    });
});

it('authorizes all users to make the request', function () {
    // Arrange
    $request = new StoreKeyControlRequest();
    
    // Act & Assert
    expect($request->authorize())->toBeTrue();
});

it('validates the request correctly', function () {
    // Arrange
    $key = Key::factory()->create();
    $person = Person::factory()->create();
    $data = [
        'key_id' => $key->id,
        'person_id' => $person->id,
        'comment' => 'Key assigned to person for maintenance',
    ];

    // Act
    $validator = validator($data, (new StoreKeyControlRequest())->rules());

    // Assert
    expect($validator->passes())->toBeTrue();
});

it('fails validation when key_id is missing', function () {
    // Arrange
    $person = Person::factory()->create();
    $data = [
        'person_id' => $person->id,
        'comment' => 'Missing key_id',
    ];

    // Act
    $response = $this->postJson('/test-key-control', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['key_id']);
});

it('fails validation when key_id is not an integer', function () {
    // Arrange
    $person = Person::factory()->create();
    $data = [
        'key_id' => 'not-an-integer',
        'person_id' => $person->id,
        'comment' => 'Invalid key_id type'
    ];

    // Act
    $response = $this->postJson('/test-key-control', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['key_id']);
});

it('fails validation when key_id does not exist', function () {
    // Arrange
    $person = Person::factory()->create();
    $data = [
        'key_id' => 99999,
        'person_id' => $person->id,
        'comment' => 'Non-existent key_id'
    ];

    // Act
    $response = $this->postJson('/test-key-control', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['key_id']);
});

it('fails validation when person_id is missing', function () {
    // Arrange
    $key = Key::factory()->create();
    $data = [
        'key_id' => $key->id,
        'comment' => 'Missing person_id'
    ];

    // Act
    $response = $this->postJson('/test-key-control', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['person_id']);
});

it('fails validation when person_id is not an integer', function () {
    // Arrange
    $key = Key::factory()->create();
    $data = [
        'key_id' => $key->id,
        'person_id' => 'not-an-integer',
        'comment' => 'Invalid person_id type'
    ];

    // Act
    $response = $this->postJson('/test-key-control', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['person_id']);
});

it('fails validation when person_id is invalid', function () {
    // Arrange
    $key = Key::factory()->create();
    $data = [
        'key_id' => $key->id,
        'person_id' => 999,
        'comment' => 'Invalid person ID',
    ];

    // Act
    $response = $this->postJson('/test-key-control', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['person_id']);
});

it('validates comment is string when provided', function () {
    // Arrange
    $key = Key::factory()->create();
    $person = Person::factory()->create();
    $data = [
        'key_id' => $key->id,
        'person_id' => $person->id,
        'comment' => ['not-a-string']
    ];

    // Act
    $response = $this->postJson('/test-key-control', $data);

    // Assert
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['comment']);
});

it('allows null comment', function () {
    // Arrange
    $key = Key::factory()->create();
    $person = Person::factory()->create();
    $data = [
        'key_id' => $key->id,
        'person_id' => $person->id,
        'comment' => null
    ];

    // Act
    $response = $this->postJson('/test-key-control', $data);

    // Assert
    $response->assertStatus(200);
});

it('passes validation when all fields are correct', function () {
    // Arrange
    $key = Key::factory()->create();
    $person = Person::factory()->create();
    $data = [
        'key_id' => $key->id,
        'person_id' => $person->id,
        'comment' => 'Key assigned successfully',
    ];

    // Act
    $response = $this->postJson('/test-key-control', $data);

    // Assert
    $response->assertStatus(200);
});