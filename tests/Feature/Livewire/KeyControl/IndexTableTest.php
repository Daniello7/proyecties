<?php

use App\Livewire\KeyControl\IndexTable;
use App\Models\KeyControl;
use App\Models\User;
use App\Models\Key;
use App\Models\Person;
use App\Models\Area;
use Spatie\Permission\Models\Role;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('shows correct initial configuration', function () {
    $component = livewire(IndexTable::class)->instance();

    expect($component)
        ->columns->toBe(['Person', 'Key', 'Deliver', 'Exit', 'Receiver', 'Entry', 'Comment', 'Actions'])
        ->select->toBe(['key_controls.*'])
        ->columnMap->toBe([
            'Key' => 'key.name',
            'Person' => 'person.name',
            'Deliver' => 'deliver.name',
            'Receiver' => 'receiver.name',
            'Exit' => 'exit_time',
            'Entry' => 'entry_time',
            'Comment' => null,
            'Actions' => null
        ])
        ->sortColumn->toBe('entry_time')
        ->sortDirection->toBe('desc');
});

it('updates key id when receiving keyUpdated event', function () {
    livewire(IndexTable::class)
        ->dispatch('keyUpdated', 5)
        ->assertSet('keyId', 5);
});

it('resets key id when receiving zoneUpdated event', function () {
    livewire(IndexTable::class)
        ->set('keyId', 5)
        ->dispatch('zoneUpdated', 3)
        ->assertSet('keyId', null)
        ->assertSet('areaId', 3);
});

it('filters by area correctly', function () {
    // Arrange
    $area1 = Area::factory()->create();
    $area2 = Area::factory()->create();
    $key1 = Key::factory()->create(['area_id' => $area1->id]);
    $key2 = Key::factory()->create(['area_id' => $area2->id]);

    $keyControl1 = KeyControl::factory()->create([
        'key_id' => $key1->id,
        'entry_time' => now()
    ]);
    $keyControl2 = KeyControl::factory()->create([
        'key_id' => $key2->id,
        'entry_time' => now()
    ]);

    // Act & Assert
    livewire(IndexTable::class)
        ->set('areaId', $area1->id)
        ->assertSee($key1->name)
        ->assertDontSee($key2->name);
});

it('filters by key correctly', function () {
    // Arrange
    $key1 = Key::factory()->create(['name' => 'Test Key 1']);
    $key2 = Key::factory()->create(['name' => 'Test Key 2']);

    $keyControl1 = KeyControl::factory()->create([
        'key_id' => $key1->id,
        'entry_time' => now()
    ]);
    $keyControl2 = KeyControl::factory()->create([
        'key_id' => $key2->id,
        'entry_time' => now()
    ]);

    // Act & Assert
    livewire(IndexTable::class)
        ->set('keyId', $key1->id)
        ->assertSee('Test Key 1')
        ->assertDontSee('Test Key 2');
});

it('applies search filter correctly', function () {
    // Arrange
    $person1 = Person::factory()->create(['name' => 'Juan Test']);
    $person2 = Person::factory()->create(['name' => 'Pedro Test']);

    KeyControl::factory()->create([
        'person_id' => $person1->id,
        'entry_time' => now()
    ]);
    KeyControl::factory()->create([
        'person_id' => $person2->id,
        'entry_time' => now()
    ]);

    // Act & Assert
    livewire(IndexTable::class)
        ->set('search', 'Juan Test')
        ->assertSee('Juan Test')
        ->assertDontSee('Pedro Test');
});

it('opens edit modal correctly', function () {
    // Arrange
    $keyControl = KeyControl::factory()->create([
        'entry_time' => now(),
        'exit_time' => now()
    ]);

    // Act & Assert
    livewire(IndexTable::class)
        ->call('openModal', 'editKeyControl', $keyControl->id)
        ->assertSet('activeModal', 'editKeyControl')
        ->assertSet('exitKey_id', $keyControl->id)
        ->assertSet('person_id', $keyControl->person_id)
        ->assertSet('key_id', $keyControl->key_id)
        ->assertSet('deliver_id', $keyControl->deliver_id)
        ->assertSet('receiver_id', $keyControl->receiver_id);
});

it('updates key control successfully', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $keyControl = KeyControl::factory()->create(['entry_time' => now()]);
    $newPerson = Person::factory()->create();

    // Act & Assert
    livewire(IndexTable::class)
        ->call('openModal', 'editKeyControl', $keyControl->id)
        ->set('person_id', $newPerson->id)
        ->call('updateKeyControl')
        ->assertHasNoErrors()
        ->assertSet('activeModal', null)
        ->assertSee(trans('messages.key-control_updated'));

    expect(KeyControl::find($keyControl->id))
        ->person_id->toBe($newPerson->id);
});

it('prevents unauthorized key control updates', function () {
    // Arrange
    $keyControl = KeyControl::factory()->create(['entry_time' => now()]);

    // Act & Assert
    livewire(IndexTable::class)
        ->call('openModal', 'editKeyControl', $keyControl->id)
        ->call('updateKeyControl')
        ->assertForbidden();
});

it('deletes key control successfully', function () {
    // Arrange
    Role::create(['name' => 'admin']);
    $this->user->assignRole('admin');

    $keyControl = KeyControl::factory()->create(['entry_time' => now()]);

    // Act & Assert
    livewire(IndexTable::class)
        ->call('openModal', 'deleteKeyControl', $keyControl->id)
        ->call('deleteKeyControl')
        ->assertHasNoErrors()
        ->assertSet('activeModal', null)
        ->assertSee(trans('messages.key-control_deleted'));

    $this->assertDatabaseMissing('key_controls', [
        'id' => $keyControl->id
    ]);
});

it('prevents unauthorized key control deletions', function () {
    // Arrange
    $keyControl = KeyControl::factory()->create(['entry_time' => now()]);

    // Act & Assert
    livewire(IndexTable::class)
        ->call('openModal', 'deleteKeyControl', $keyControl->id)
        ->call('deleteKeyControl')
        ->assertForbidden();
});

it('closes modal and resets form', function () {
    // Arrange
    $keyControl = KeyControl::factory()->create(['entry_time' => now()]);

    // Act & Assert
    livewire(IndexTable::class)
        ->call('openModal', 'editKeyControl', $keyControl->id)
        ->call('closeModal')
        ->assertSet('activeModal', null)
        ->assertSet('exitKey_id', null)
        ->assertSet('exitKey', null);
});

it('shows only key controls with entry time', function () {
    // Arrange
    $completedControl = KeyControl::factory()->create(['entry_time' => now()]);
    $pendingControl = KeyControl::factory()->create(['entry_time' => null]);

    // Act & Assert
    $component = livewire(IndexTable::class);

    expect($component->instance()->getKeyControlRows()->pluck('id')->toArray())
        ->toContain($completedControl->id)
        ->not->toContain($pendingControl->id);
});
