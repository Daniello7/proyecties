<?php

namespace Tests\Feature\Events;

use App\Events\NotifyContactVisitorEvent;
use App\Models\Person;
use App\Models\PersonEntry;
use App\Models\InternalPerson;
use ErrorException;

beforeEach(function() {
    // Arrange
    $this->visitor = Person::factory()->create();
    
    $this->contactPerson = Person::factory()->create();

    $this->internalPerson = InternalPerson::factory()->create([
        'email' => 'john@example.com',
        'person_id' => $this->contactPerson->id
    ]);

    $this->personEntry = PersonEntry::factory()->create([
        'person_id' => $this->visitor->id,
        'internal_person_id' => $this->internalPerson->id,
        'arrival_time' => now()->toDateTimeString()
    ]);

    $this->personEntry->load(['internalPerson.person', 'person']);
});

it('should construct event with correct email', function() {
    // Act
    $event = new NotifyContactVisitorEvent($this->personEntry);

    // Assert
    expect($event->email)->toBe('john@example.com');
});

it('should throw exception when internal person relationship is not loaded', function() {
    // Arrange
    $entry = PersonEntry::factory()->create([
        'internal_person_id' => $this->internalPerson->id,
        'arrival_time' => now()->toDateTimeString()
    ]);
    
    // Forzamos que la relación sea null
    $entry->setRelation('internalPerson', null);
    
    // Act & Assert
    expect(fn() => new NotifyContactVisitorEvent($entry))
        ->toThrow(ErrorException::class);
});