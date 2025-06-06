<?php

namespace Tests\Feature\Livewire\PersonEntries;

use App\Events\NotifyContactVisitorEvent;
use App\Livewire\PersonEntries\IndexTable;
use App\Models\Person;
use App\Models\PersonEntry;
use App\Models\InternalPerson;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Spatie\Permission\Models\Role;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('mounts with correct initial configuration', function () {
    // Act & Assert
    livewire(IndexTable::class)
        ->assertSet('columns', ['DNI', 'Name', 'Company', 'Contact', 'Latest Visit', 'Reason', 'Comment', 'Actions'])
        ->assertSet('sortColumn', 'exit_time')
        ->assertSet('sortDirection', 'desc')
        ->assertSet('activeModal', null)
        ->assertSet('id', null)
        ->assertSet('person', null);
});

it('can sort columns', function () {
    // Act & Assert
    livewire(IndexTable::class)
        ->call('sortBy', 'person.name')
        ->assertSet('sortColumn', 'person.name')
        ->assertSet('sortDirection', 'asc')
        // Verificar cambio de dirección
        ->call('sortBy', 'person.name')
        ->assertSet('sortDirection', 'desc');
});

it('shows only latest entries for external people', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $externalPerson = Person::factory()->create(['name' => 'External Person']);

    // Crear múltiples entradas para la misma persona externa
    $oldEntry = PersonEntry::factory()->create([
        'person_id' => $externalPerson->id,
        'internal_person_id' => $internalPerson->id,
        'exit_time' => now()->subDays(2)
    ]);

    $latestEntry = PersonEntry::factory()->create([
        'person_id' => $externalPerson->id,
        'internal_person_id' => $internalPerson->id,
        'exit_time' => now()
    ]);

    // Act & Assert
    livewire(IndexTable::class)
        ->assertSee($latestEntry->exit_time)
        ->assertDontSee($oldEntry->exit_time);
});

it('can search entries', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $externalPerson = Person::factory()->create([
        'name' => 'Juan Test',
        'company' => 'Test Company'
    ]);

    PersonEntry::factory()->create([
        'person_id' => $externalPerson->id,
        'internal_person_id' => $internalPerson->id,
        'exit_time' => now()
    ]);

    // Act & Assert
    livewire(IndexTable::class)
        ->set('search', 'Juan Test')
        ->assertSee('Juan Test')
        ->assertSee('Test Company');
});

it('can open edit modal', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $entry = PersonEntry::factory()->create();

    // Act & Assert
    livewire(IndexTable::class)
        ->call('openModal', 'editEntry', $entry->id)
        ->assertSet('activeModal', 'editEntry')
        ->assertSet('id', $entry->id);
});

it('can open create modal', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $person = Person::factory()->create();
    $person->personEntries()->save(PersonEntry::factory()->make());

    // Act & Assert
    livewire(IndexTable::class)
        ->call('openModal', 'createEntry', $person->id)
        ->assertSet('activeModal', 'createEntry')
        ->assertSet('person.id', $person->id);
});

it('can close modal', function () {
    // Arrange
    $entry = PersonEntry::factory()->create();

    // Act & Assert
    $component = livewire(IndexTable::class);
    $component->call('openModal', 'modal', $entry->id);

    $component
        ->call('closeModal')
        ->assertSet('activeModal', null)
        ->assertSet('id', null)
        ->assertSet('entry', null);
});

it('can update person entry', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $internalPerson = InternalPerson::factory()->create();
    $entry = PersonEntry::factory()->create([
        'internal_person_id' => $internalPerson->id,
        'reason' => 'Visit',
        'comment' => 'Old comment'
    ]);

    // Act & Assert
    $component = livewire(IndexTable::class);
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

    $entry = PersonEntry::factory()->create();

    // Act & Assert
    livewire(IndexTable::class)
        ->call('destroyPersonEntry', $entry->id)
        ->assertSet('activeModal', null);

    $this->assertDatabaseMissing('person_entries', ['id' => $entry->id]);
});

it('dispatches notification event when creating entry with notify flag', function () {
    // Arrange
    Event::fake();
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();
    $person->personEntries()->save(PersonEntry::factory()
        ->create(['internal_person_id' => $internalPerson->id]));


    // Act
    $component = livewire(IndexTable::class);
    $component->call('openModal', 'createEntry', $person->id);

    $component
        ->set('notify', true)
        ->set('reason', 'Visit')
        ->set('internal_person_id', $internalPerson->id)
        ->set('person_id', $person->id)
        ->call('storePersonEntry');

    // Assert
    Event::assertDispatched(NotifyContactVisitorEvent::class);
});

it('shows correct rules pdf based on entry reason', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create();
    $person->personEntries()->save(PersonEntry::factory()
        ->create(['internal_person_id' => $internalPerson->id]));

    $reasons = [
        'Charge' => 'driver-rules',
        'Discharge' => 'driver-rules',
        'Cleaning' => 'cleaning-rules',
        'Visit' => 'visitor-rules'
    ];

    foreach ($reasons as $reason => $expectedRoute) {
        // Act
        $component = livewire(IndexTable::class);
        $component->call('openModal', 'createEntry', $person->id);

        $component
            ->set('reason', $reason)
            ->set('internal_person_id', $internalPerson->id)
            ->set('person_id', $person->id)
            ->call('storePersonEntry')
            ->assertDispatched('openRulesPdf', function ($event, $data) use ($expectedRoute, $person) {
                return str_contains($data['url'], route($expectedRoute, ['person' => $person]));
            });
    }
});