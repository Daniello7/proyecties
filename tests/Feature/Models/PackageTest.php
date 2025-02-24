<?php

namespace Tests\Feature\Models;

use App\Models\Package;
use App\Models\Person;
use App\Models\User;
use App\Models\InternalPerson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;

uses(RefreshDatabase::class);

it('creates a new package with valid attributes', function () {
    // Arrange
    Person::factory()->create();
    $user = User::factory()->create();
    $internalPerson = InternalPerson::factory()->create();

    // Act
    $package = Package::create([
        'type' => 'entry',
        'agency' => 'DHL',
        'package_count' => 1,
        'external_entity' => 'External Entity',
        'receiver_user_id' => $user->id,
        'deliver_user_id' => $user->id,
        'internal_person_id' => $internalPerson->id,
        'retired_by' => 'John Doe',
        'entry_time' => now(),
        'exit_time' => now()->addHours(1),
        'comment' => 'Test package',
    ]);

    // Assert
    expect($package)->toBeInstanceOf(Package::class)
        ->and($package->type)->toBe('entry')
        ->and($package->agency)->toBe('DHL')
        ->and($package->receiver_user_id)->toBe($user->id);
});

it('fails to create a package with invalid agency', function () {
    // Arrange
    Person::factory()->create();
    $user = User::factory()->create();
    $internalPerson = InternalPerson::factory()->create();

    // Act & Assert
    expect(fn() => Package::create([
        'type' => 'entry',
        'agency' => 'Invalid Agency', // Invalid agency
        'package_count' => 1,
        'external_entity' => 'External Entity',
        'receiver_user_id' => $user->id,
        'deliver_user_id' => $user->id,
        'internal_person_id' => $internalPerson->id,
        'collected_by' => 'John Doe',
        'entry_time' => now(),
        'exit_time' => now()->addHours(1),
        'comment' => 'Test package',
    ]))->toThrow(QueryException::class);
});

it('updates a package successfully', function () {
    // Arrange
    Person::factory()->create();
    $user = User::factory()->create();
    $internalPerson = InternalPerson::factory()->create();
    $package = Package::create([
        'type' => 'entry',
        'agency' => 'DHL',
        'package_count' => 1,
        'external_entity' => 'External Entity',
        'receiver_user_id' => $user->id,
        'deliver_user_id' => $user->id,
        'internal_person_id' => $internalPerson->id,
        'collected_by' => 'John Doe',
        'entry_time' => now(),
        'exit_time' => now()->addHours(1),
        'comment' => 'Test package',
    ]);

    // Act
    $package->update([
        'comment' => 'Updated comment',
    ]);

    // Assert
    $package->refresh();
    expect($package->comment)->toBe('Updated comment');
});

it('fails when required fields are missing', function () {

    // Act & Assert
    expect(fn() => Package::create([
    ]))->toThrow(QueryException::class);
});
