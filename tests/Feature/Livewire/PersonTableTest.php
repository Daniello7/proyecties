<?php

use App\Livewire\PersonTable;
use App\Models\InternalPerson;
use App\Models\Person;
use App\Models\PersonEntry;
use App\Models\User;
use Spatie\Permission\Models\Role;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can render person table component', function () {
    // Arrange
    Person::factory()->count(5)->create();

    // Act & Assert
    livewire(PersonTable::class)
        ->assertViewIs('livewire.person-table')
        ->assertSuccessful();
});

it('can sort people by column', function () {
    // Arrange
    Person::factory()->create(['name' => 'Carlos']);
    Person::factory()->create(['name' => 'Ana']);

    // Act & Assert
    livewire(PersonTable::class)
        ->assertSet('sortDirection', 'asc')
        ->call('sortBy', 'name')
        ->assertSet('sortDirection', 'desc')
        ->assertSet('sortColumn', 'name')
        ->call('sortBy', 'name')
        ->assertSet('sortDirection', 'asc');
});

it('can filter people by search term', function () {
    // Arrange
    Person::factory()->create(['name' => 'Juan']);
    Person::factory()->create(['name' => 'Ana']);

    // Act & Assert
    livewire(PersonTable::class)
        ->set('search', 'Juan')
        ->assertSee('Juan')
        ->assertDontSee('Ana');
});

it('can open create person modal', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    // Act & Assert
    livewire(PersonTable::class)
        ->call('openModal', 'createPerson')
        ->assertSet('activeModal', 'createPerson');
});

it('can store new person', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $personData = [
        'document_number' => '12345678A',
        'name' => 'Juan',
        'last_name' => 'Pérez',
        'company' => 'Test Company',
        'comment' => 'Test comment'
    ];

    // Act & Assert
    livewire(PersonTable::class)
        ->set('document_number', $personData['document_number'])
        ->set('name', $personData['name'])
        ->set('last_name', $personData['last_name'])
        ->set('company', $personData['company'])
        ->set('comment', $personData['comment'])
        ->call('storePerson')
        ->assertHasNoErrors()
        ->assertSee(__('messages.person_created'));

    $this->assertDatabaseHas('people', $personData);
});

it('can open edit person modal', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $person = Person::factory()->create();

    // Act & Assert
    livewire(PersonTable::class)
        ->call('openModal', 'editPerson', $person->id)
        ->assertSet('activeModal', 'editPerson')
        ->assertSet('person.id', $person->id)
        ->assertSet('name', $person->name);
});

it('can update person', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $person = Person::factory()->create();

    $newData = [
        'document_number' => '87654321B',
        'name' => 'María',
        'last_name' => 'García',
        'company' => 'New Company',
    ];

    // Act & Assert
    livewire(PersonTable::class)
        ->call('openModal', 'editPerson', $person->id)
        ->set('document_number', $newData['document_number'])
        ->set('name', $newData['name'])
        ->set('last_name', $newData['last_name'])
        ->set('company', $newData['company'])
        ->call('updatePerson')
        ->assertHasNoErrors()
        ->assertSee(__('messages.person_updated'));

    $this->assertDatabaseHas('people', $newData);
});

it('can delete person', function () {
    // Arrange
    Role::create(['name' => 'admin']);
    $this->user->assignRole('admin');

    $person = Person::factory()->create();

    // Act & Assert
    livewire(PersonTable::class)
        ->call('openModal', 'deletePerson', $person->id)
        ->call('deletePerson')
        ->assertSee(__('messages.person_deleted'));

    $this->assertDatabaseMissing('people', ['id' => $person->id]);
});

it('cannot store person without permission', function () {
    // Arrange
    $personData = [
        'document_number' => '12345678A',
        'name' => 'Juan',
        'last_name' => 'Pérez',
        'company' => 'Test Company',
    ];

    // Act & Assert
    livewire(PersonTable::class)
        ->set('document_number', $personData['document_number'])
        ->set('name', $personData['name'])
        ->set('last_name', $personData['last_name'])
        ->set('company', $personData['company'])
        ->call('storePerson')
        ->assertForbidden();
});

it('validates required fields when storing person', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    // Act & Assert
    livewire(PersonTable::class)
        ->call('storePerson')
        ->assertHasErrors(['document_number', 'name', 'last_name', 'company']);
});

it('validates unique document number when storing person', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    Person::factory()->create(['document_number' => '12345678A']);

    // Act & Assert
    livewire(PersonTable::class)
        ->set('document_number', '12345678A')
        ->set('name', 'Juan')
        ->set('last_name', 'Pérez')
        ->set('company', 'Test Company')
        ->call('storePerson')
        ->assertHasErrors(['document_number']);
});

it('can open create entry modal', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $person = Person::factory()->create();

    // Act & Assert
    livewire(PersonTable::class)
        ->call('openModal', 'createEntry', $person->id)
        ->assertSet('activeModal', 'createEntry')
        ->assertSet('person.id', $person->id);
});

it('closes modal and resets properties', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $person = Person::factory()->create();

    // Act & Assert
    livewire(PersonTable::class)
        ->call('openModal', 'editPerson', $person->id)
        ->assertSet('activeModal', 'editPerson')
        ->call('closeModal')
        ->assertSet('activeModal', null)
        ->assertSet('person', null)
        ->assertSet('id', null);
});


it('applies search filter across all searchable columns', function () {
    // Arrange
    $p1 = Person::factory()->create([
        'name' => 'Juan',
        'company' => 'ABC Corp'
    ]);
    $p2 = Person::factory()->create([
        'name' => 'María',
        'company' => 'Ma Industries'
    ]);

    // Act & Assert
    livewire(PersonTable::class)
        ->set('search', $p1->name)
        ->assertSee($p1->name)
        ->assertSee($p1->company)
        ->assertDontSee($p2->name)
        ->set('search', $p2->name)
        ->assertSee($p2->name)
        ->assertSee($p2->company)
        ->assertDontSee($p1->name);
});

it('excludes internal persons from table', function () {
    // Arrange
    $internal = InternalPerson::factory()->create();
    $internal->person->update(['name' => 'Internal Person']);

    Person::factory()->create(['name' => 'External Person']);

    // Act & Assert
    livewire(PersonTable::class)
        ->assertSee('External Person')
        ->assertDontSee('Internal Person');
});

it('loads person entry data when opening create entry modal with existing entry', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $person = Person::factory()->create();
    $entry = PersonEntry::factory()->create([
        'person_id' => $person->id,
        'exit_time' => now()
    ]);

    // Act & Assert
    livewire(PersonTable::class)
        ->call('openModal', 'createEntry', $person->id)
        ->assertSet('activeModal', 'createEntry')
        ->assertSet('person.id', $person->id)
        ->assertSet('entry.id', $entry->id)
        ->assertSet('comment', null);
});