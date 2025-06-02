<?php

use App\Events\NotifyContactPackageEvent;
use App\Models\Package;
use App\Models\Person;
use App\Models\InternalPerson;
use App\Models\User;

beforeEach(function() {
    // Arrange
    $this->person = Person::factory()->create([
        'name' => 'John Doe'
    ]);
    
    $this->internalPerson = InternalPerson::factory()->create([
        'email' => 'john@example.com',
        'person_id' => $this->person->id
    ]);

    $this->receiverUser = User::factory()->create([
        'name' => 'Jane Doe'
    ]);
    
    $this->package = Package::factory()->create([
        'type' => 'entry',
        'internal_person_id' => $this->internalPerson->id,
        'receiver_user_id' => $this->receiverUser->id,
        'external_entity' => 'Amazon',
        'agency' => Package::AGENCIES[0],
        'package_count' => 2,
        'entry_time' => '2025-01-15 10:30:00',
        'comment' => 'Handle with care'
    ]);
});

it('should dispatch event with correct email data', function() {
    // Act
    $event = new NotifyContactPackageEvent($this->package);

    // Assert
    expect($event->email)->toBe('john@example.com');
});

it('should throw exception when internal person relationship is not loaded', function() {
    // Arrange
    $package = Package::factory()->create([
        'internal_person_id' => $this->internalPerson->id
    ]);
    
    // Simulamos un paquete sin la relación cargada
    $packageWithoutLoadedRelation = Package::find($package->id);
    $packageWithoutLoadedRelation->setRelation('internalPerson', null);

    // Act & Assert
    expect(fn() => new NotifyContactPackageEvent($packageWithoutLoadedRelation))
        ->toThrow(Exception::class);
});

it('should throw exception when internal person has invalid email format', function() {
    // Arrange
    $internalPersonWithInvalidEmail = InternalPerson::factory()->create([
        'email' => 'not-a-valid-email'
    ]);
    
    $packageWithInvalidEmail = Package::factory()->create([
        'internal_person_id' => $internalPersonWithInvalidEmail->id
    ]);

    // Act & Assert
    expect(fn() => new NotifyContactPackageEvent($packageWithInvalidEmail))
        ->toThrow(Error::class);
});