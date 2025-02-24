<?php

use App\Livewire\KeyControlTable;
use App\Models\Key;
use App\Models\KeyControl;
use App\Models\Person;
use App\Models\User;
use Livewire\Livewire;

it('renders key control rows correctly for home view', function () {
    // Arrange
    User::factory()->create();
    Key::factory()->create();
    Person::factory()->create();
    KeyControl::factory()->create(['entry_time' => null]);

    // Act
    $component = Livewire::test(KeyControlTable::class)
        ->set('isHomeView', true);

    // Assert
    $component->assertSee('Person')
        ->assertSee('Key')
        ->assertSee('Comment')
        ->assertSee('Actions');
});

it('renders key control rows correctly for index view', function () {
    // Arrange
    User::factory()->create();
    Key::factory()->create();
    Person::factory()->create();
    KeyControl::factory()->create(['entry_time' => now()]);
    $component = Livewire::test(KeyControlTable::class)
        ->set('isHomeView', false);

    // Assert
    $component->assertSee('Person')
        ->assertSee('Key')
        ->assertSee('Deliver')
        ->assertSee('Exit')
        ->assertSee('Receiver')
        ->assertSee('Entry')
        ->assertSee('Comment')
        ->assertSee('Actions');
});

it('sorts by the correct column', function () {
    // Arrange
    User::factory()->create();
    Key::factory()->create();
    Person::factory()->create();
    KeyControl::factory()->create(['entry_time' => now()]);
    $component = Livewire::test(KeyControlTable::class)
        ->set('isHomeView', false)
        ->set('sortColumn', 'entry_time')
        ->set('sortDirection', 'asc');

    // Act
    $component->call('sortBy', 'Entry');

    // Assert
    $component->assertSeeInOrder(['Entry']);
});

