<?php

use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('generates a valid cleaning rules PDF', function () {
    // Arrange
    actingAsPorter();

    $person = Person::factory()->create();

    // Act
    $response = $this->get(route('cleaning-rules', ['person' => $person->id]));

    // Assert
    $response->assertHeader('Content-Type', 'application/pdf');
});

it('generates a valid visitor rules PDF', function () {
    // Arrange
    actingAsPorter();

    $person = Person::factory()->create();

    // Act
    $response = $this->get(route('visitor-rules', ['person' => $person->id]));

    // Assert
    $response->assertHeader('Content-Type', 'application/pdf');
});

it('generates a valid driver rules PDF', function () {
    // Arrange
    actingAsPorter();

    $person = Person::factory()->create();

    // Act
    $response = $this->get(route('driver-rules', ['person' => $person->id]));

    // Assert
    $response->assertHeader('Content-Type', 'application/pdf');
});
