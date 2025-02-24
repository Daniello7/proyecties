<?php

use App\Models\Person;
use Illuminate\Database\QueryException;

it('creates a person with valid attributes', function () {
    // Arrange
    $person = Person::create([
        'name' => 'John',
        'last_name' => 'Doe',
        'document_number' => '123456789',
        'company' => 'ACME Corp',
        'comment' => 'Test comment',
    ]);

    // Act & Assert
    expect($person)->toBeInstanceOf(Person::class)
        ->and($person->name)->toBe('John')
        ->and($person->last_name)->toBe('Doe')
        ->and($person->document_number)->toBe('123456789')
        ->and($person->company)->toBe('ACME Corp')
        ->and($person->comment)->toBe('Test comment');
});

it('fails when document_number is not unique', function () {
    // Arrange
    $person1 = Person::create([
        'name' => 'John',
        'last_name' => 'Doe',
        'document_number' => '123456789',
        'company' => 'ACME Corp',
        'comment' => 'Test comment',
    ]);

    // Act & Assert
    expect(fn() => Person::create([
        'name' => 'Jane',
        'last_name' => 'Doe',
        'document_number' => '123456789',
        'company' => 'XYZ Corp',
        'comment' => 'Another comment',
    ]))->toThrow(QueryException::class);
});
