<?php

use App\Events\NotifyContactVisitorEvent;
use App\Mail\NotifyContactVisitorMail;
use App\Models\Person;
use App\Models\PersonEntry;
use App\Models\InternalPerson;
use Illuminate\Support\Facades\Mail;

it('sends email when visitor arrives with comment', function () {
    // Arrange
    Mail::fake();
    
    $person = Person::factory()->create([
        'document_number' => '12345678A',
        'name' => 'John',
        'last_name' => 'Doe'
    ]);

    $internalPerson = InternalPerson::factory()->create([
        'email' => 'internal@example.com'
    ]);

    $personEntry = PersonEntry::factory()->create([
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'arrival_time' => now(),
        'comment' => 'Test comment'
    ]);

    // Act
    event(new NotifyContactVisitorEvent($personEntry));

    // Assert
    Mail::assertQueued(NotifyContactVisitorMail::class, function ($mail) use ($internalPerson) {
        return $mail->hasTo($internalPerson->email);
    });
});

it('sends email when visitor arrives without comment', function () {
    // Arrange
    Mail::fake();
    
    $person = Person::factory()->create([
        'document_number' => '12345678A',
        'name' => 'John',
        'last_name' => 'Doe'
    ]);

    $internalPerson = InternalPerson::factory()->create([
        'email' => 'internal@example.com'
    ]);

    $personEntry = PersonEntry::factory()->create([
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'arrival_time' => now(),
        'comment' => ''
    ]);

    // Act
    event(new NotifyContactVisitorEvent($personEntry));

    // Assert
    Mail::assertQueued(NotifyContactVisitorMail::class, function ($mail) use ($internalPerson) {
        return $mail->hasTo($internalPerson->email);
    });
});

it('sends exactly one email per event', function () {
    // Arrange
    Mail::fake();
    
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();
    $personEntry = PersonEntry::factory()->create([
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'arrival_time' => now()
    ]);

    // Act
    event(new NotifyContactVisitorEvent($personEntry));

    // Assert
    Mail::assertQueued(NotifyContactVisitorMail::class, 1);
});