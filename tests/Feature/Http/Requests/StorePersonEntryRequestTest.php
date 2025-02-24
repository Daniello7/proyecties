<?php

namespace Tests\Feature\Http\Requests\PersonEntry;

use App\Http\Requests\PersonEntry\StorePersonEntryRequest;
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
        'comment' => 'Valid entry',
    ];

    // Act
    $validator = Validator::make($data, (new StorePersonEntryRequest())->rules());

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
        'comment' => 'Missing person_id',
    ];

    // Act
    $validator = Validator::make($data, (new StorePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('person_id'))->toBeTrue();
});

it('fails validation when internal_person_id is missing', function () {
    // Arrange
    $person = Person::factory()->create();

    $data = [
        'person_id' => $person->id,
        'reason' => PersonEntry::REASONS[0],
        'comment' => 'Missing internal_person_id',
    ];

    // Act
    $validator = Validator::make($data, (new StorePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('internal_person_id'))->toBeTrue();
});

it('fails validation when reason is missing', function () {
    // Arrange
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();

    $data = [
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'comment' => 'Missing reason',
    ];

    // Act
    $validator = Validator::make($data, (new StorePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('reason'))->toBeTrue();
});

it('fails validation when reason is not in allowed values', function () {
    // Arrange
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();

    $data = [
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'reason' => 'invalid_reason',
        'comment' => 'Invalid reason',
    ];

    // Act
    $validator = Validator::make($data, (new StorePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('reason'))->toBeTrue();
});
