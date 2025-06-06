<?php

use App\Models\Person;
use App\Models\PersonDocument;

it('has the correct fillable attributes', function () {
    // Arrange & Act
    $document = new PersonDocument();
    
    // Assert
    expect($document->getFillable())->toBe([
        'filename',
        'original_name',
        'type',
        'size',
        'path'
    ]);
});

it('belongs to a person', function () {
    // Arrange
    $person = Person::factory()->create();
    $document = PersonDocument::factory()->create(['person_id' => $person->id]);
    
    // Act & Assert
    expect($document->person)
        ->toBeInstanceOf(Person::class)
        ->and($document->person->id)->toBe($person->id);
});

it('can be created with all fillable attributes', function () {
    // Arrange
    $person = Person::factory()->create();
    $attributes = [
        'person_id' => $person->id,
        'filename' => 'document123.pdf',
        'original_name' => 'original_document.pdf',
        'type' => 'application/pdf',
        'size' => 1024,
        'path' => 'documents/2025/06/document123.pdf'
    ];

    // Act
    $person->documents()->create($attributes);
    $document = $person->documents()->latest()->first();

    // Assert
    expect($document)->toBeInstanceOf(PersonDocument::class)
        ->person_id->toBe($person->id)
        ->filename->toBe('document123.pdf')
        ->original_name->toBe('original_document.pdf')
        ->type->toBe('application/pdf')
        ->size->toBe(1024)
        ->path->toBe('documents/2025/06/document123.pdf');
});


it('can be associated with a person after creation', function () {
    // Arrange
    $person = Person::factory()->create();
    $document = PersonDocument::factory()->create();
    
    // Act
    $document->person()->associate($person);
    $document->save();
    
    // Assert
    expect($document->person_id)->toBe($person->id)
        ->and($document->person)->toBeInstanceOf(Person::class);
});