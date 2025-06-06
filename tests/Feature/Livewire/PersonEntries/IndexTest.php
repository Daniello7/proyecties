<?php

namespace Tests\Feature\Livewire\PersonEntries;

use App\Livewire\PersonEntries\Index;
use App\Models\User;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('mounts with latest entries table opened by default', function () {
    // Act & Assert
    livewire(Index::class)
        ->assertSet('openedLastestEntriesTable', true)
        ->assertSet('openedPersonListTable', false)
        ->assertSet('openedPersonCreate', false);
});

it('can open latest entries table', function () {
    // Arrange
    $component = livewire(Index::class);
    $component->set('openedLastestEntriesTable', false);
    $component->set('openedPersonListTable', true);
    
    // Act & Assert
    $component
        ->call('openLastestEntriesTable')
        ->assertSet('openedLastestEntriesTable', true)
        ->assertSet('openedPersonListTable', false)
        ->assertSet('openedPersonCreate', false);
});

it('can open person list table', function () {
    // Act & Assert
    livewire(Index::class)
        ->call('openPersonListTable')
        ->assertSet('openedLastestEntriesTable', false)
        ->assertSet('openedPersonListTable', true)
        ->assertSet('openedPersonCreate', false);
});

it('can open person create form', function () {
    // Act & Assert
    livewire(Index::class)
        ->call('openPersonCreate')
        ->assertSet('openedLastestEntriesTable', false)
        ->assertSet('openedPersonListTable', false)
        ->assertSet('openedPersonCreate', true);
});

it('can close all sections', function () {
    // Arrange
    $component = livewire(Index::class);
    $component->set('openedLastestEntriesTable', true);
    $component->set('openedPersonListTable', true);
    $component->set('openedPersonCreate', true);
    
    // Act & Assert
    $component
        ->call('closeAll')
        ->assertSet('openedLastestEntriesTable', false)
        ->assertSet('openedPersonListTable', false)
        ->assertSet('openedPersonCreate', false);
});

it('renders index-table component when latest entries is opened', function () {
    // Act & Assert
    livewire(Index::class)
        ->assertSet('openedLastestEntriesTable', true)
        ->assertSeeLivewire('person-entries.index-table');
});

it('renders person-table component when person list is opened', function () {
    // Act & Assert
    livewire(Index::class)
        ->call('openPersonListTable')
        ->assertSeeLivewire('person-table');
});

it('shows correct navigation options', function () {
    // Act & Assert
    livewire(Index::class)
        ->assertSee(__('Latest Records'))
        ->assertSee(__('Person List'))
        ->assertSee(__('Options'));
});