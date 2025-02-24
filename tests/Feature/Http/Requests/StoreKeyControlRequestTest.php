<?php

namespace Tests\Feature\Http\Requests\KeyControl;

use App\Models\Key;
use App\Models\Person;
use App\Http\Requests\KeyControl\StoreKeyControlRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
    $validator = Validator::make($data, (new StoreKeyControlRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('key_id'))->toBeTrue();
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
    $validator = Validator::make($data, (new StoreKeyControlRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('person_id'))->toBeTrue();
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
    $validator = Validator::make($data, (new StoreKeyControlRequest())->rules());

    // Assert
    expect($validator->fails())->toBeFalse();
});
