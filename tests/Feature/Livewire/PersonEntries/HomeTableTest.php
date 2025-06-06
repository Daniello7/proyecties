<?php

use App\Livewire\PersonEntries\HomeTable;
use App\Models\Person;
use App\Models\PersonEntry;
use App\Models\InternalPerson;
use App\Models\User;
use App\Jobs\GenerateActiveEntriesPdfJob;
use App\Jobs\GenerateActiveEntriesExcelJob;
use Spatie\Permission\Models\Role;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can mount component with correct configuration', function () {
    // Act
    $component = livewire(HomeTable::class);

    // Assert
    $component
        ->assertSet('columns', ['Name', 'Company', 'Contact', 'Comment', 'Actions'])
        ->assertSet('sortColumn', 'arrival_time')
        ->assertSet('sortDirection', 'asc');
});

it('can sort columns', function () {
    // Act & Assert
    livewire(HomeTable::class)
        ->call('sortBy', 'person.name')
        ->assertSet('sortColumn', 'person.name')
        ->assertSet('sortDirection', 'asc')
        // Verificar cambio de dirección
        ->call('sortBy', 'person.name')
        ->assertSet('sortColumn', 'person.name')
        ->assertSet('sortDirection', 'desc');
});

it('can search person entries', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $externalPerson = Person::factory()->create([
        'name' => 'Juan Test',
        'company' => 'Test Company'
    ]);

    $internalPerson = InternalPerson::factory()->create([
        'person_id' => Person::factory()->create([
            'name' => 'Contact Person'
        ])->id
    ]);

    PersonEntry::factory()->create([
        'person_id' => $externalPerson->id,
        'internal_person_id' => $internalPerson->id,
        'exit_time' => null,
        'arrival_time' => now()
    ]);

    // Act & Assert
    livewire(HomeTable::class)
        ->set('search', 'Juan Test')
        ->assertSee('Juan Test')
        ->assertSee('Test Company');
});

it('can update entry time', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $personEntry = PersonEntry::factory()->create([
        'entry_time' => null,
        'exit_time' => null
    ]);

    // Act
    $component = livewire(HomeTable::class)
        ->call('updateEntry', $personEntry->id);

    // Assert
    $component->assertHasNoErrors();
    $this->assertNotNull(PersonEntry::find($personEntry->id)->entry_time);
    $component->assertSee(__('messages.person-entry_updated'));
});

it('can update exit time', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $personEntry = PersonEntry::factory()->create([
        'exit_time' => null
    ]);

    // Act
    $component = livewire(HomeTable::class)
        ->call('updateExit', $personEntry->id);

    // Assert
    $component->assertHasNoErrors();
    $this->assertNotNull(PersonEntry::find($personEntry->id)->exit_time);
    $component->assertSee(__('messages.person-entry_exited'));
});

it('can cancel person entry', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $personEntry = PersonEntry::factory()->create();

    // Act
    $component = livewire(HomeTable::class)
        ->call('cancelPersonEntry', $personEntry->id);

    // Assert
    $component->assertHasNoErrors();
    $this->assertDatabaseMissing('person_entries', ['id' => $personEntry->id]);
    $component->assertSee(__('messages.person-entry_deleted'));
});

it('can dispatch pdf generation job', function () {
    // Arrange
    Queue::fake();

    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    // Crear una persona externa (sin internal_person)
    $externalPerson = Person::factory()
        ->create(['name' => 'External Person']);

    // Crear una persona interna para el contacto
    $internalPerson = Person::factory()
        ->has(InternalPerson::factory())
        ->create(['name' => 'Internal Contact']);

    $personEntry = PersonEntry::factory()->create([
        'person_id' => $externalPerson->id,
        'internal_person_id' => InternalPerson::where('person_id', $internalPerson->id)->first()->id,
        'exit_time' => null
    ]);

    // Act
    $component = livewire(HomeTable::class);
    $component->call('generateActiveEntriesPdf');

    // Assert
    Queue::assertPushed(GenerateActiveEntriesPdfJob::class, function ($job) use ($component, $personEntry) {
        return $job->columns === $component->columns &&
            in_array($personEntry->id, $job->entries_id) &&
            $job->user_id === auth()->id();
    });
});


it('can dispatch excel generation job', function () {
    // Arrange
    Queue::fake();
    
    $internalPerson = InternalPerson::factory()->create();
    $externalPerson = Person::factory()->create();

    $personEntry = PersonEntry::factory()->create([
        'person_id' => $externalPerson->id,
        'internal_person_id' => $internalPerson->id,
        'exit_time' => null
    ]);

    // Act
    livewire(HomeTable::class)
        ->call('generateActiveEntriesExcel');

    // Assert
    Queue::assertPushed(GenerateActiveEntriesExcelJob::class, function ($job) use ($personEntry) {
        return in_array($personEntry->id, $job->entries_id);
    });
});

it('shows only active entries', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $externalPerson1 = Person::factory()->create(['name' => 'Active Person']);
    $externalPerson2 = Person::factory()->create(['name' => 'Inactive Person']);

    $activeEntry = PersonEntry::factory()->create([
        'person_id' => $externalPerson1->id,
        'internal_person_id' => $internalPerson->id,
        'exit_time' => null
    ]);
    
    $inactiveEntry = PersonEntry::factory()->create([
        'person_id' => $externalPerson2->id,
        'internal_person_id' => $internalPerson->id,
        'exit_time' => now()
    ]);

    // Act
    $component = livewire(HomeTable::class);

    // Assert
    $component
        ->assertSee($activeEntry->person->name)
        ->assertDontSee($inactiveEntry->person->name);
});

it('cannot update entry without permission', function () {
    // Arrange
    $personEntry = PersonEntry::factory()->create([
        'entry_time' => null,
        'exit_time' => null
    ]);

    // Act & Assert
    livewire(HomeTable::class)
        ->call('updateEntry', $personEntry->id)
        ->assertStatus(403);
});

it('only shows external people entries', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $internalPerson = InternalPerson::factory()->create();

    $externalPerson = Person::factory()->create();

    $bothPerson = Person::factory()
        ->has(InternalPerson::factory())
        ->create();

    $externalEntry = PersonEntry::factory()->create([
        'person_id' => $externalPerson->id,
        'internal_person_id' => $internalPerson->id,
        'exit_time' => null
    ]);

    $internalEntry = PersonEntry::factory()->create([
        'person_id' => $bothPerson->id,
        'internal_person_id' => $internalPerson->id,
        'exit_time' => null
    ]);

    // Act & Assert
    livewire(HomeTable::class)
        ->assertSee($externalPerson->name)
        ->assertDontSee($bothPerson->name);
});

it('dispatches documentGenerated event after generating pdf', function () {
    // Arrange
    Queue::fake();
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $internalPerson = InternalPerson::factory()->create();
    $externalPerson = Person::factory()->create();

    PersonEntry::factory()->create([
        'person_id' => $externalPerson->id,
        'internal_person_id' => $internalPerson->id,
        'exit_time' => null
    ]);

    // Act & Assert
    livewire(HomeTable::class)
        ->call('generateActiveEntriesPdf')
        ->assertDispatched('documentGenerated');
});

it('dispatches documentGenerated event after generating excel', function () {
    // Arrange
    Queue::fake();
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $internalPerson = InternalPerson::factory()->create();
    $externalPerson = Person::factory()->create();

    PersonEntry::factory()->create([
        'person_id' => $externalPerson->id,
        'internal_person_id' => $internalPerson->id,
        'exit_time' => null
    ]);

    // Act & Assert
    livewire(HomeTable::class)
        ->call('generateActiveEntriesExcel')
        ->assertDispatched('documentGenerated');
});