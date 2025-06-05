<?php

use App\Livewire\Packages\IndexTable;
use App\Models\Package;
use App\Models\User;
use App\Models\InternalPerson;
use Spatie\Permission\Models\Role;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can mount component and configure columns', function () {
    // Act
    $component = livewire(IndexTable::class);

    // Assert
    $component
        ->assertSet('columns', [
            'Type', 'Agency', 'Sender', 'Destination', 'Entry', 'Receiver',
            'Exit', 'Deliver', 'Package Count', 'Retired By', 'Comment', 'Actions'
        ])
        ->assertSet('sortColumn', 'exit_time')
        ->assertSet('sortDirection', 'asc');
});

it('can sort columns', function () {
    // Arrange
    $component = livewire(IndexTable::class);

    // Act & Assert
    $component->call('sortBy', 'type')
        ->assertSet('sortColumn', 'type')
        ->assertSet('sortDirection', 'asc');

    // Verify sort direction toggles
    $component->call('sortBy', 'type')
        ->assertSet('sortColumn', 'type')
        ->assertSet('sortDirection', 'desc');
});

it('can search packages', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $package = Package::factory()->create([
        'agency' => 'SEUR',
        'internal_person_id' => $internalPerson->id
    ]);

    // Act
    $component = livewire(IndexTable::class)
        ->set('search', 'SEUR');

    // Assert
    $component->assertSee('SEUR');
});


it('can open edit modal', function () {
    // Arrange
    $package = Package::factory()->create();

    // Act
    $component = livewire(IndexTable::class)
        ->call('openModal', 'editPackage', $package->id);

    // Assert
    $component->assertSet('activeModal', 'editPackage')
        ->assertSet('package_id', $package->id)
        ->assertSet('type', $package->type)
        ->assertSet('agency', $package->agency)
        ->assertSet('external_entity', $package->external_entity);
});

it('can close modal', function () {
    // Arrange
    $component = livewire(IndexTable::class)
        ->set('activeModal', 'editPackage')
        ->set('package_id', 1);

    // Act
    $component->call('closeModal');

    // Assert
    $component->assertSet('activeModal', null)
        ->assertSet('package_id', null);
});

it('can update package', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $internalPerson = InternalPerson::factory()->create();
    $package = Package::factory()->create([
        'internal_person_id' => $internalPerson->id
    ]);

    // Act
    $component = livewire(IndexTable::class)
        ->set('package_id', $package->id)
        ->set('agency', 'DHL')
        ->set('external_entity', 'New Entity')
        ->set('package_count', 2)
        ->set('internal_person_id', $internalPerson->id)
        ->set('entry_time', now()->toDateTimeString())
        ->set('exit_time', now()->addHour()->toDateTimeString())
        ->set('comment', 'Test comment')
        ->call('updatePackage', $package->id);

    // Assert
    $component->assertHasNoErrors();

    $this->assertDatabaseHas('packages', [
        'id' => $package->id,
        'agency' => 'DHL',
        'external_entity' => 'New Entity',
        'package_count' => 2,
        'internal_person_id' => $internalPerson->id,
        'comment' => 'Test comment'
    ]);
});

it('can delete package', function () {
    // Arrange
    Role::create(['name' => 'admin']);
    $this->user->assignRole('admin');

    $package = Package::factory()->create();

    // Act
    $component = livewire(IndexTable::class)
        ->call('deletePackage', $package->id);

    // Assert
    $this->assertSoftDeleted('packages', ['id' => $package->id]);
});

it('paginates results', function () {
    // Arrange
    Package::factory()->count(51)->create();

    // Act
    $component = livewire(IndexTable::class);
    $packages = $component->viewData('rows');

    // Assert
    expect($packages)->toBeInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class)
        ->and($packages->perPage())->toBe(50);
});

it('requires authorization to update package', function () {
    // Arrange
    $package = Package::factory()->create();
    
    // No asignamos ningún rol al usuario
    // Por defecto no tendrá permisos para actualizar

    // Act & Assert
    $component = livewire(IndexTable::class)
        ->call('updatePackage', $package->id)
        ->assertForbidden();
});

it('requires authorization to delete package', function () {
    // Arrange
    $package = Package::factory()->create();
    
    // Incluso con rol 'porter' no debería poder eliminar
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    // Act & Assert
    $component = livewire(IndexTable::class)
        ->call('deletePackage', $package->id)
        ->assertForbidden();
});