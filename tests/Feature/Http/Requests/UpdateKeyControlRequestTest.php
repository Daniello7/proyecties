<?php

namespace Tests\Feature\Http\Requests\KeyControl;

use App\Http\Requests\KeyControl\UpdateKeyControlRequest;
use App\Models\Person;
use Illuminate\Support\Facades\Validator;

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
    $validator = Validator::make($data, (new UpdateKeyControlRequest())->rules());

    // Assert
    expect($validator->fails())->toBeFalse();
});

it('fails validation when person_id is missing', function () {
    // Arrange
    $data = [
        'comment' => 'No person ID provided',
        'exit_time' => now()->subHour()->toDateTimeString(),
        'entry_time' => now()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdateKeyControlRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('person_id'))->toBeTrue();
});

it('fails validation when exit_time is missing', function () {
    // Arrange
    $person = Person::factory()->create();

    $data = [
        'person_id' => $person->id,
        'comment' => 'Missing exit time',
        'entry_time' => now()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdateKeyControlRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('exit_time'))->toBeTrue();
});

it('fails validation when entry_time is missing', function () {
    // Arrange
    $person = Person::factory()->create();

    $data = [
        'person_id' => $person->id,
        'comment' => 'Missing entry time',
        'exit_time' => now()->subHour()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdateKeyControlRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('entry_time'))->toBeTrue();
});

it('fails validation when exit_time is not a valid date', function () {
    // Arrange
    $person = Person::factory()->create();

    $data = [
        'person_id' => $person->id,
        'comment' => 'Invalid exit time',
        'exit_time' => 'not-a-date',
        'entry_time' => now()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdateKeyControlRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('exit_time'))->toBeTrue();
});

it('fails validation when entry_time is not a valid date', function () {
    // Arrange
    $person = Person::factory()->create();

    $data = [
        'person_id' => $person->id,
        'comment' => 'Invalid entry time',
        'exit_time' => now()->subHour()->toDateTimeString(),
        'entry_time' => 'not-a-date',
    ];

    // Act
    $validator = Validator::make($data, (new UpdateKeyControlRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('entry_time'))->toBeTrue();
});
