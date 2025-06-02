<?php

use App\Models\Person;
use App\Models\InternalPerson;
use App\Models\PersonEntry;
use App\Http\Requests\PersonEntry\UpdatePersonEntryRequest;
use Illuminate\Support\Facades\Validator;

beforeEach(function () {
    $this->person = Person::factory()->create();
    $this->internalPerson = InternalPerson::factory()->create();
});

it('validates with correct data', function () {
    // Arrange
    $data = [
        'internal_person_id' => $this->internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
        'comment' => 'Test comment',
        'arrival_time' => now()->toDateTimeString(),
        'entry_time' => now()->addMinutes(5)->toDateTimeString(),
        'exit_time' => now()->addHour()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonEntryRequest())->rules());

    // Assert
    expect($validator->passes())->toBeTrue();
});

it('requires internal_person_id', function () {
    // Arrange
    $data = [
        'reason' => PersonEntry::REASONS[0],
        'comment' => 'Test comment',
        'arrival_time' => now()->toDateTimeString(),
        'exit_time' => now()->addHour()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('internal_person_id'))->toBeTrue();
});

it('requires reason', function () {
    // Arrange
    $data = [
        'internal_person_id' => $this->internalPerson->id,
        'comment' => 'Test comment',
        'arrival_time' => now()->toDateTimeString(),
        'exit_time' => now()->addHour()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('reason'))->toBeTrue();
});

it('validates reason must be in valid reasons list', function () {
    // Arrange
    $data = [
        'internal_person_id' => $this->internalPerson->id,
        'reason' => 'invalid_reason',
        'comment' => 'Test comment',
        'arrival_time' => now()->toDateTimeString(),
        'exit_time' => now()->addHour()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('reason'))->toBeTrue();
});

it('accepts null comment', function () {
    // Arrange
    $data = [
        'internal_person_id' => $this->internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
        'comment' => null,
        'arrival_time' => now()->toDateTimeString(),
        'exit_time' => now()->addHour()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonEntryRequest())->rules());

    // Assert
    expect($validator->passes())->toBeTrue();
});

it('validates internal_person_id must exist', function () {
    // Arrange
    $data = [
        'internal_person_id' => 999999,
        'reason' => PersonEntry::REASONS[0],
        'comment' => 'Test comment',
        'arrival_time' => now()->toDateTimeString(),
        'exit_time' => now()->addHour()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('internal_person_id'))->toBeTrue();
});

it('requires arrival_time', function () {
    // Arrange
    $data = [
        'internal_person_id' => $this->internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
        'comment' => 'Test comment',
        'exit_time' => now()->addHour()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('arrival_time'))->toBeTrue();
});

it('validates arrival_time must be a valid date', function () {
    // Arrange
    $data = [
        'internal_person_id' => $this->internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
        'comment' => 'Test comment',
        'arrival_time' => 'invalid-date',
        'exit_time' => now()->addHour()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('arrival_time'))->toBeTrue();
});

it('validates entry_time must be a valid date when provided', function () {
    // Arrange
    $data = [
        'internal_person_id' => $this->internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
        'comment' => 'Test comment',
        'arrival_time' => now()->toDateTimeString(),
        'entry_time' => 'invalid-date',
        'exit_time' => now()->addHour()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('entry_time'))->toBeTrue();
});

it('requires exit_time', function () {
    // Arrange
    $data = [
        'internal_person_id' => $this->internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
        'comment' => 'Test comment',
        'arrival_time' => now()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('exit_time'))->toBeTrue();
});

it('validates exit_time must be a valid date', function () {
    // Arrange
    $data = [
        'internal_person_id' => $this->internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
        'comment' => 'Test comment',
        'arrival_time' => now()->toDateTimeString(),
        'exit_time' => 'invalid-date',
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('exit_time'))->toBeTrue();
});

it('validates comment maximum length', function () {
    // Arrange
    $data = [
        'internal_person_id' => $this->internalPerson->id,
        'reason' => PersonEntry::REASONS[0],
        'comment' => str_repeat('a', 256),
        'arrival_time' => now()->toDateTimeString(),
        'exit_time' => now()->addHour()->toDateTimeString(),
    ];

    // Act
    $validator = Validator::make($data, (new UpdatePersonEntryRequest())->rules());

    // Assert
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('comment'))->toBeTrue();
});