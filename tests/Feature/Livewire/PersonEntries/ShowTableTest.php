<?php

namespace Tests\Feature\Livewire\PersonEntries;

use App\Livewire\PersonEntries\ShowTable;
use App\Models\Person;
use App\Models\PersonEntry;
use App\Models\InternalPerson;
use App\Models\User;
use Spatie\Permission\Models\Role;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('mounts with correct initial configuration', function () {
    // Arrange
    $person = Person::factory()->create();
    
    // Act & Assert
    livewire(ShowTable::class, ['person_id' => $person->id])
        ->assertSet('columns', ['Contact', 'Reason', 'Porter', 'Arrival', 'Entry', 'Exit', 'Comment', 'Actions'])
        ->assertSet('sortColumn', 'exit_time')
        ->assertSet('sortDirection', 'desc')
        ->assertSet('activeModal', null)
        ->assertSet('id', null)
        ->assertSet('person', null);
});

it('can sort columns', function () {
    // Arrange
    $person = Person::factory()->create();
    
    // Act & Assert
    livewire(ShowTable::class, ['person_id' => $person->id])
        ->call('sortBy', 'person_entries.reason')
        ->assertSet('sortColumn', 'person_entries.reason')
        ->assertSet('sortDirection', 'asc')
        // Verificar cambio de dirección
        ->call('sortBy', 'person_entries.reason')
        ->assertSet('sortDirection', 'desc');
});

it('shows person entries', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $person = Person::factory()->create();
    
    $entry = PersonEntry::factory()->create([
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'user_id' => $this->user->id,
        'exit_time' => now()
    ]);

    // Act & Assert
    livewire(ShowTable::class, ['person_id' => $person->id])
        ->assertSee(__($entry->reason))
        ->assertSee($this->user->name)
        ->assertSee($entry->exit_time);
});

it('can search entries', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create([
        'person_id' => Person::factory()->create(['name' => 'Contact Test'])->id
    ]);
    $person = Person::factory()->create();
    
    $entry = PersonEntry::factory()->create([
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'reason' => 'Visit',
        'exit_time' => now()
    ]);

    // Act & Assert
    livewire(ShowTable::class, ['person_id' => $person->id])
        ->set('search', 'Contact Test')
        ->assertSee('Contact Test')
        ->assertSee(__($entry->reason));
});

it('can open edit modal', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');
    
    $person = Person::factory()->create();
    $entry = PersonEntry::factory()->create(['person_id' => $person->id]);

    // Act & Assert
    livewire(ShowTable::class, ['person_id' => $person->id])
        ->call('openModal', 'editEntry', $entry->id)
        ->assertSet('activeModal', 'editEntry')
        ->assertSet('id', $entry->id)
        ->assertSet('entry.id', $entry->id);
});

it('can close modal', function () {
    // Arrange
    $person = Person::factory()->create();
    $entry = PersonEntry::factory()->create(['person_id' => $person->id]);

    // Act & Assert
    $component = livewire(ShowTable::class, ['person_id' => $person->id]);
    $component->call('openModal', 'modal', $entry->id);

    $component
        ->call('closeModal')
        ->assertSet('activeModal', null)
        ->assertSet('id', null)
        ->assertSet('entry', null)
        ->assertSet('person_id', $person->id); // Debe mantener person_id
});

it('can update person entry', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();
    $entry = PersonEntry::factory()->create([
        'person_id' => $person->id,
        'internal_person_id' => $internalPerson->id,
        'reason' => 'Visit',
        'comment' => 'Old comment'
    ]);

    // Act & Assert
    $component = livewire(ShowTable::class, ['person_id' => $person->id]);
    $component->call('openModal', 'editEntry', $entry->id);

    $component
        ->set('comment', 'Updated comment')
        ->set('reason', 'Cleaning')
        ->set('internal_person_id', $internalPerson->id)
        ->set('arrival_time', now())
        ->set('exit_time', now())
        ->call('updatePersonEntry')
        ->assertHasNoErrors()
        ->assertSet('activeModal', null);

    $this->assertDatabaseHas('person_entries', [
        'id' => $entry->id,
        'comment' => 'Updated comment',
        'reason' => 'Cleaning'
    ]);
});

it('can destroy person entry', function () {
    // Arrange
    Role::create(['name' => 'admin']);
    $this->user->assignRole('admin');

    $person = Person::factory()->create();
    $entry = PersonEntry::factory()->create(['person_id' => $person->id]);

    // Act & Assert
    livewire(ShowTable::class, ['person_id' => $person->id])
        ->call('destroyPersonEntry', $entry->id)
        ->assertSet('activeModal', null);

    $this->assertDatabaseMissing('person_entries', ['id' => $entry->id]);
});