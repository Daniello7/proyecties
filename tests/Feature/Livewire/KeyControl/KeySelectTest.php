<?php

use App\Livewire\KeyControl\KeySelect;
use App\Livewire\KeyControl\IndexTable;
use App\Models\Key;
use App\Models\Area;
use App\Models\User;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('shows correct initial state', function () {
    livewire(KeySelect::class)
        ->assertSet('zone', 0)
        ->assertSet('keyId', 0)
        ->assertSee('Zona no seleccionada');
});

it('shows select a key message when zone has keys', function () {
    // Arrange
    $area = Area::factory()->create();
    Key::factory(3)->create(['area_id' => $area->id]);

    // Act & Assert
    livewire(KeySelect::class)
        ->set('zone', $area->id)
        ->assertSee('Selecciona una llave')
        ->assertDontSee('Zona no seleccionada');
});

it('displays keys for selected zone', function () {
    // Arrange
    $area = Area::factory()->create();
    $keys = Key::factory(3)->create([
        'area_id' => $area->id,
        'name' => 'Llave de Prueba'
    ]);

    // Act & Assert
    livewire(KeySelect::class)
        ->set('zone', $area->id)
        ->assertSee("{$area->id}.{$keys[0]->area_key_number} - Llave de Prueba")
        ->assertSee("{$area->id}.{$keys[1]->area_key_number} - Llave de Prueba")
        ->assertSee("{$area->id}.{$keys[2]->area_key_number} - Llave de Prueba");
});

it('does not show keys from different zones', function () {
    // Arrange
    $area1 = Area::factory()->create();
    $area2 = Area::factory()->create();

    $key1 = Key::factory()->create([
        'area_id' => $area1->id,
        'name' => 'Llave Zona 1'
    ]);

    $key2 = Key::factory()->create([
        'area_id' => $area2->id,
        'name' => 'Llave Zona 2'
    ]);

    // Act & Assert
    livewire(KeySelect::class)
        ->set('zone', $area1->id)
        ->assertSee('Llave Zona 1')
        ->assertDontSee('Llave Zona 2');
});

it('dispatches keyUpdated event when key is selected', function () {
    // Arrange
    $area = Area::factory()->create();
    $key = Key::factory()->create(['area_id' => $area->id]);

    // Act & Assert
    livewire(KeySelect::class)
        ->set('zone', $area->id)
        ->set('keyId', $key->id)
        ->assertDispatched('keyUpdated', $key->id);
});

it('updates zone and resets keyId when zone is changed', function () {
    // Arrange
    $area1 = Area::factory()->create();
    $area2 = Area::factory()->create();
    $key = Key::factory()->create(['area_id' => $area1->id]);

    // Act & Assert
    $component = livewire(KeySelect::class)
        ->set('zone', $area1->id)
        ->set('keyId', $key->id)
        ->set('zone', $area2->id);

    $component
        ->assertSet('zone', $area2->id)
        ->assertSet('keyId', 0)
        ->assertDispatched('zoneUpdated', $area2->id);
});

it('maintains synchronization with index table through events', function () {
    // Arrange
    $area = Area::factory()->create();
    $key = Key::factory()->create(['area_id' => $area->id]);

    $indexTable = livewire(IndexTable::class);
    $keySelect = livewire(KeySelect::class);

    // Act & Assert Zone Change
    $keySelect
        ->set('zone', $area->id)
        ->assertDispatched('zoneUpdated', $area->id);

    $indexTable
        ->dispatch('zoneUpdated', $area->id)
        ->assertSet('areaId', $area->id)
        ->assertSet('keyId', null);

    // Act & Assert Key Selection
    $keySelect
        ->set('keyId', $key->id)
        ->assertDispatched('keyUpdated', $key->id);

    $indexTable
        ->dispatch('keyUpdated', $key->id)
        ->assertSet('keyId', $key->id);
});

it('shows formatted key options', function () {
    // Arrange
    $area = Area::factory()->create();
    $key = Key::factory()->create([
        'area_id' => $area->id,
        'area_key_number' => 123,
        'name' => 'Llave Maestra'
    ]);

    // Act & Assert
    livewire(KeySelect::class)
        ->set('zone', $area->id)
        ->assertSee("$key->area_id.$key->area_key_number - $key->name");
});