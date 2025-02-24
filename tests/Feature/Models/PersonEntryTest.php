<?php

use App\Models\PersonEntry;
use App\Models\User;
use App\Models\Person;
use App\Models\InternalPerson;
use Illuminate\Database\QueryException;

it('creates a person entry', function () {
    // Arrange
    $user = User::factory()->create();
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();

    // Act
    $personEntry = PersonEntry::create([
        'user_id' => $user->id,
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'comment' => 'Test entry',
        'reason' => 'Charge',
        'arrival_time' => now(),
        'entry_time' => now(),
        'exit_time' => now()->addHour(),
    ]);

    // Assert
    expect($personEntry)->toBeInstanceOf(PersonEntry::class)
        ->and($personEntry->user_id)->toBe($user->id)
        ->and($personEntry->person_id)->toBe($person->id)
        ->and($personEntry->internal_person_id)->toBe($internalPerson->id)
        ->and($personEntry->reason)->toBe('Charge')
        ->and($personEntry->comment)->toBe('Test entry');
});

it('validates reasons for a person entry', function () {
    // Arrange
    $user = User::factory()->create();
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();

    // Act
    $personEntry = PersonEntry::create([
        'user_id' => $user->id,
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'comment' => 'Test entry',
        'reason' => 'Work in Facilities',
        'arrival_time' => now(),
        'entry_time' => now(),
        'exit_time' => now()->addHour(),
    ]);

    // Assert
    expect(in_array($personEntry->reason, PersonEntry::REASONS))->toBeTrue();
});

it('checks relationships with user, person, and internal person', function () {
    // Arrange
    $user = User::factory()->create();
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();
    $personEntry = PersonEntry::create([
        'user_id' => $user->id,
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'comment' => 'Test entry',
        'reason' => 'Visit',
        'arrival_time' => now(),
        'entry_time' => now(),
        'exit_time' => now()->addHour(),
    ]);

    // Act
    $userRelation = $personEntry->user;
    $personRelation = $personEntry->person;
    $internalPersonRelation = $personEntry->internalPerson;

    // Assert
    expect($userRelation)->toBeInstanceOf(User::class)
        ->and($personRelation)->toBeInstanceOf(Person::class)
        ->and($internalPersonRelation)->toBeInstanceOf(InternalPerson::class);
});

it('fails when reason is invalid', function () {
    // Arrange
    $user = User::factory()->create();
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();

    // Act & Assert
    expect(fn() => PersonEntry::create([
        'user_id' => $user->id,
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'comment' => 'Test entry',
        'reason' => 'Invalid reason',
        'arrival_time' => now(),
        'entry_time' => now(),
        'exit_time' => now()->addHour(),
    ]))->toThrow(QueryException::class);
});
