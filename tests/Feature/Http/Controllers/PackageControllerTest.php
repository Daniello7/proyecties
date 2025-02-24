<?php

use App\Models\InternalPerson;
use App\Models\Person;
use App\Models\User;
use App\Models\Package;

it('stores a package successfully', function () {
    // Arrange
    Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();
    $user = User::factory()->create();
    actingAsPorter($user);

    $data = [
        'agency' => Package::AGENCIES[0],
        'external_entity' => 'Test External Entity',
        'internal_person_id' => $internalPerson->id,
        'receiver_user_id' => $user->id,
        'package_count' => 1,
    ];

    // Act
    $response = $this->post(route('packages.store'), $data);

    // Assert
    $response->assertRedirect(route('control-access'));
    $this->assertDatabaseHas('packages', [
        'agency' => Package::AGENCIES[0],
        'external_entity' => 'Test External Entity',
        'internal_person_id' => $internalPerson->id,
        'package_count' => 1,
        'receiver_user_id' => $user->id,
        'type' => 'entry'
    ]);
});

it('stores an exit package successfully', function () {
    // Arrange
    Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();

    $user = User::factory()->create();
    actingAsPorter($user);

    $data = [
        'agency' => Package::AGENCIES[0],
        'external_entity' => 'Test External Entity Exit',
        'internal_person_id' => $internalPerson->id,
        'receiver_user_id' => $user->id,
        'package_count' => 2,
    ];

    // Act
    $response = $this->post(route('packages.storeExit'), $data);

    // Assert
    $response->assertRedirect(route('control-access'));
    $this->assertDatabaseHas('packages', [
        'agency' => Package::AGENCIES[0],
        'external_entity' => 'Test External Entity Exit',
        'internal_person_id' => $internalPerson->id,
        'package_count' => 2,
        'receiver_user_id' => $user->id,
        'type' => 'exit',
    ]);
});
