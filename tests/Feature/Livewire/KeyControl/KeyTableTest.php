<?php

use App\Livewire\KeyControl\KeyTable;
use App\Models\Area;
use App\Models\Key;
use App\Models\User;
use Spatie\Permission\Models\Role;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('shows correct initial configuration', function () {
    $component = livewire(KeyTable::class)->instance();

    expect($component)
        ->columns->toBe(['Area', 'Key', 'Actions'])
        ->select->toBe(['id', 'area_key_number', 'name', 'area_id'])
        ->columnMap->toBe([
            'Area' => 'area_id',
            'Key' => 'name',
            'Actions' => null
        ]);
});

it('displays keys correctly', function () {
    // Arrange
    $area = Area::factory()->create(['name' => 'Test Area']);
    $key = Key::factory()->create([
        'area_id' => $area->id,
        'area_key_number' => 123,
        'name' => 'Test Key'
    ]);

    // Act & Assert
    livewire(KeyTable::class)
        ->assertSee("{$area->id} - {$area->name}")
        ->assertSee("$key->area_id.$key->area_key_number - $key->name");
});

it('filters keys by search term', function () {
    // Arrange
    $area = Area::factory()->create(['name' => 'Search Area']);
    $key1 = Key::factory()->create([
        'area_id' => $area->id,
        'name' => 'Unique Key Name'
    ]);
    $key2 = Key::factory()->create([
        'area_id' => $area->id,
        'name' => 'Different Key'
    ]);

    // Act & Assert
    livewire(KeyTable::class)
        ->set('search', 'Unique')
        ->assertSee('Unique Key Name')
        ->assertDontSee('Different Key');
});

it('opens create modal correctly', function () {
    Role::create(['name' => 'admin']);
    $this->user->assignRole('admin');

    livewire(KeyTable::class)
        ->call('openModal', 'createKey')
        ->assertSet('activeModal', 'createKey')
        ->assertSet('key_id', null);
});

it('opens edit modal and loads key data', function () {
    // Arrange
    $key = Key::factory()->create();

    // Act & Assert
    livewire(KeyTable::class)
        ->call('openModal', 'editKey', $key->id)
        ->assertSet('activeModal', 'editKey')
        ->assertSet('key_id', $key->id)
        ->assertSet('area_id', $key->area_id)
        ->assertSet('name', $key->name);
});

it('creates key successfully', function () {
    // Arrange
    Role::create(['name' => 'admin']);
    $this->user->assignRole('admin');
    
    $area = Area::factory()->create();
    
    // Act & Assert
    livewire(KeyTable::class)
        ->call('openModal', 'createKey')
        ->set('area_id', $area->id)
        ->set('name', 'New Test Key')
        ->call('storeKey')
        ->assertHasNoErrors()
        ->assertSet('activeModal', null)
        ->assertSee(__('messages.key_created'));

    $this->assertDatabaseHas('keys', [
        'area_id' => $area->id,
        'name' => 'New Test Key'
    ]);
});

it('updates key successfully', function () {
    // Arrange
    Role::create(['name' => 'admin']);
    $this->user->assignRole('admin');
    
    $key = Key::factory()->create(['name' => 'Old Name']);

    // Act & Assert
    livewire(KeyTable::class)
        ->call('openModal', 'editKey', $key->id)
        ->set('name', 'Updated Name')
        ->call('updateKey')
        ->assertHasNoErrors()
        ->assertSet('activeModal', null)
        ->assertSee(__('messages.key_updated'));

    $this->assertDatabaseHas('keys', [
        'id' => $key->id,
        'name' => 'Updated Name'
    ]);
});

it('deletes key successfully', function () {
    // Arrange
    Role::create(['name' => 'admin']);
    $this->user->assignRole('admin');
    
    $key = Key::factory()->create();

    // Act & Assert
    livewire(KeyTable::class)
        ->call('openModal', 'deleteKey', $key->id)
        ->call('deleteKey')
        ->assertSet('activeModal', null)
        ->assertSee(__('messages.key_deleted'));

    $this->assertDatabaseMissing('keys', ['id' => $key->id]);
});

it('prevents unauthorized key creation', function () {
    livewire(KeyTable::class)
        ->call('storeKey')
        ->assertForbidden();
});

it('prevents unauthorized key update', function () {
    // Arrange
    $key = Key::factory()->create();

    // Act & Assert
    livewire(KeyTable::class)
        ->call('openModal', 'editKey', $key->id)
        ->call('updateKey')
        ->assertForbidden();
});

it('prevents unauthorized key deletion', function () {
    // Arrange
    $key = Key::factory()->create();

    // Act & Assert
    livewire(KeyTable::class)
        ->call('openModal', 'deleteKey', $key->id)
        ->call('deleteKey')
        ->assertForbidden();
});

it('validates required fields when creating key', function () {
    // Arrange
    Role::create(['name' => 'admin']);
    $this->user->assignRole('admin');

    // Act & Assert
    livewire(KeyTable::class)
        ->call('storeKey')
        ->assertHasErrors(['area_id', 'name']);
});

it('closes modal and resets form', function () {
    // Arrange
    $key = Key::factory()->create();

    // Act & Assert
    livewire(KeyTable::class)
        ->call('openModal', 'editKey', $key->id)
        ->call('closeModal')
        ->assertSet('activeModal', null)
        ->assertSet('key_id', null)
        ->assertSet('key', null);
});