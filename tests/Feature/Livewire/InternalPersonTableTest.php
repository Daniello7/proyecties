<?php

namespace Tests\Feature\Livewire;

use App\Livewire\InternalPersonTable;
use App\Models\InternalPerson;
use App\Models\Person;
use Livewire\Livewire;

beforeEach(function () {
    actingAsPorter();
});

it('loads the internal people with correct columns', function () {
    // Arrange
    Person::factory()->create();
    InternalPerson::factory()->create();

    // Act
    $component = Livewire::test(InternalPersonTable::class);

    // Assert
    $component->assertSee('Nº Employer')
        ->assertSee('Name')
        ->assertSee('Last Name')
        ->assertSee('Actions');
});

it('applies search filter when search term is entered', function () {
    // Arrange
    $person = Person::factory()->create(['name' => 'John Doe']);
    $internalPerson = InternalPerson::factory()->create(['person_id' => $person->id]);

    // Act
    $component = Livewire::test(InternalPersonTable::class)
        ->set('search', 'John')
        ->call('getInternalPeople');

    // Assert
    $component->assertSee($internalPerson->id)
        ->assertSee('John Doe');
});

it('sorts internal people by selected column', function () {
    // Arrange
    Person::factory(2)->create();
    InternalPerson::factory()->create(['id' => 1, 'person_id' => 1]);
    InternalPerson::factory()->create(['id' => 2, 'person_id' => 2]);

    // Act
    $component = Livewire::test(InternalPersonTable::class)
        ->call('sortBy', 'Nº Employer');

    // Assert
    $component->assertSeeInOrder([1, 2]);
});

it('does not apply sorting when column is invalid', function () {
    // Arrange
    Person::factory()->create();
    InternalPerson::factory()->create(['id' => 1, 'person_id' => 1]);

    // Act
    $component = Livewire::test(InternalPersonTable::class)
        ->call('sortBy', 'Actions');

    // Assert
    $component->assertSee(1);
});

it('renders internal people list correctly with pagination', function () {
    // Arrange
    Person::factory()->create();
    InternalPerson::factory()->count(15)->create();

    // Act
    $component = Livewire::test(InternalPersonTable::class)
        ->set('search', '');

    // Assert
    $component->assertSee('Nº Employer')
        ->assertSeeInOrder([1, 2]);
});
