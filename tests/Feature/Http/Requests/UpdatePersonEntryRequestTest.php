<?php

namespace Tests\Feature\Http\Requests\PersonEntry;

use App\Http\Requests\PersonEntry\UpdatePersonEntryRequest;
use App\Models\InternalPerson;
use App\Models\Person;
use App\Models\User;
use App\Models\PersonEntry;
use Illuminate\Support\Facades\Validator;

it('passes validation when all fields are correct', function () {
    // Arrange
    $user = User::factory()->create();
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();

    $data = [
        'user_id' => $user->id,
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
        'comment' => 'Valid update',
        'arrival_time' => now()->toDateTimeString(),
        'entry_time' => now()->toDateTimeString(),
        'exit_time' => now()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeFalse();
});

it('fails validation when person_id is missing', function () {
    // Arrange
    Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();

    $data = [
        'internal_person_id' => $internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
        'arrival_time' => now()->toDateTimeString(),
        'entry_time' => now()->toDateTimeString(),
        'exit_time' => now()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('person_id'))->toBeTrue();
});

it('fails validation when reason is invalid', function () {
    // Arrange
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();

    $data = [
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'reason' => 'invalid_reason',
        'arrival_time' => now()->toDateTimeString(),
        'entry_time' => now()->toDateTimeString(),
        'exit_time' => now()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('reason'))->toBeTrue();
});

it('fails validation when arrival_time is missing', function () {
    // Arrange
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();

    $data = [
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
        'entry_time' => now()->toDateTimeString(),
        'exit_time' => now()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('arrival_time'))->toBeTrue();
});

it('fails validation when exit_time is not a valid date', function () {
    // Arrange
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();

    $data = [
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
        'arrival_time' => now()->toDateTimeString(),
        'entry_time' => now()->toDateTimeString(),
        'exit_time' => 'invalid_date',
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('exit_time'))->toBeTrue();
});
