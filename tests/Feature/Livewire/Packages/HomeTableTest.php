<?php

use App\Livewire\Packages\HomeTable;
use App\Models\Package;
use App\Models\Person;
use App\Models\InternalPerson;
use App\Models\User;
use Spatie\Permission\Models\Role;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('shows correct initial configuration', function () {
    $component = livewire(HomeTable::class)->instance();

    expect($component)
        ->columns->toBe(['Type', 'Agency', 'Sender', 'Destination', 'Entry', 'Comment', 'Actions'])
        ->select->toBe([
            'packages.id',
            'packages.type',
            'packages.agency',
            'packages.external_entity',
            'packages.internal_person_id',
            'packages.entry_time',
            'packages.comment',
        ])
        ->sortColumn->toBe('packages.type')
        ->sortDirection->toBe('asc');
});

it('displays active packages correctly', function () {
    // Arrange
    $person = Person::factory()->create(['name' => 'Test Person', 'last_name' => 'Last Name']);
    $internalPerson = InternalPerson::factory()->create(['person_id' => $person->id]);

    $package = Package::factory()->create([
        'internal_person_id' => $internalPerson->id,
        'type' => 'entry',
        'agency' => 'SEUR',
        'external_entity' => 'Test Entity',
        'exit_time' => null
    ]);

    // Act & Assert
    livewire(HomeTable::class)
        ->assertSee(__('Entry'))
        ->assertSee('SEUR')
        ->assertSee('Test Entity')
        ->assertSee('Test Person')
        ->assertSee('Last Name');
});

it('filters packages by search term', function () {
    // Arrange
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create(['person_id' => $person->id]);

    $package1 = Package::factory()->create([
        'internal_person_id' => $internalPerson->id,
        'external_entity' => 'Unique Package Name',
        'exit_time' => null
    ]);

    $package2 = Package::factory()->create([
        'internal_person_id' => $internalPerson->id,
        'external_entity' => 'Different Package',
        'exit_time' => null
    ]);

    // Act & Assert
    livewire(HomeTable::class)
        ->set('search', 'Unique')
        ->assertSee('Unique Package Name')
        ->assertDontSee('Different Package');
});

it('opens comment input correctly', function () {
    // Arrange
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create(['person_id' => $person->id]);
    $package = Package::factory()->create([
        'internal_person_id' => $internalPerson->id,
        'exit_time' => null
    ]);

    // Act & Assert
    livewire(HomeTable::class)
        ->call('openCommentInput', $package->id)
        ->assertSet('activePackageCommentInput', "package_{$package->id}")
        ->assertSet('packageComment', '');
});

it('updates package comment successfully', function () {
    // Arrange
    Role::create(['name' => 'admin']);
    $this->user->assignRole('admin');

    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create(['person_id' => $person->id]);
    $package = Package::factory()->create([
        'internal_person_id' => $internalPerson->id,
        'exit_time' => null
    ]);

    // Act & Assert
    livewire(HomeTable::class)
        ->call('openCommentInput', $package->id)
        ->set('packageComment', 'New Comment')
        ->call('updatePackageComment', $package->id)
        ->assertSet('activePackageCommentInput', null)
        ->assertSee(__('messages.comment_updated'));

    $this->assertDatabaseHas('packages', [
        'id' => $package->id,
        'comment' => 'New Comment'
    ]);
});

it('closes comment input correctly', function () {
    livewire(HomeTable::class)
        ->set('activePackageCommentInput', 'package_1')
        ->call('closeCommentInput')
        ->assertSet('activePackageCommentInput', null);
});

it('updates package successfully', function () {
    // Arrange
    Role::create(['name' => 'admin']);
    $this->user->assignRole('admin');

    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create(['person_id' => $person->id]);
    $package = Package::factory()->create([
        'internal_person_id' => $internalPerson->id,
        'exit_time' => null
    ]);

    // Act & Assert
    livewire(HomeTable::class)
        ->call('updatePackage', $package->id)
        ->assertSee(__('messages.package_updated'));

    $this->assertDatabaseHas('packages', [
        'id' => $package->id,
        'receiver_user_id' => $this->user->id
    ]);
});

it('deletes package successfully', function () {
    // Arrange
    Role::create(['name' => 'admin']);
    $this->user->assignRole('admin');

    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create(['person_id' => $person->id]);
    $package = Package::factory()->create([
        'internal_person_id' => $internalPerson->id,
        'exit_time' => null
    ]);

    // Act & Assert
    livewire(HomeTable::class)
        ->call('deletePackage', $package->id)
        ->assertSee(__('messages.package_deleted'));

    $this->assertSoftDeleted('packages', ['id' => $package->id]);
});

it('prevents unauthorized package comment update', function () {
    // Arrange
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create(['person_id' => $person->id]);
    $package = Package::factory()->create([
        'internal_person_id' => $internalPerson->id,
        'exit_time' => null
    ]);

    livewire(HomeTable::class)
        ->call('updatePackageComment', $package->id)
        ->assertForbidden();
});

it('prevents unauthorized package update', function () {
    // Arrange
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create(['person_id' => $person->id]);
    $package = Package::factory()->create([
        'internal_person_id' => $internalPerson->id,
        'exit_time' => null
    ]);

    livewire(HomeTable::class)
        ->call('updatePackage', $package->id)
        ->assertForbidden();
});

it('prevents unauthorized package deletion', function () {
    // Arrange
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create(['person_id' => $person->id]);
    $package = Package::factory()->create([
        'internal_person_id' => $internalPerson->id,
        'exit_time' => null
    ]);

    livewire(HomeTable::class)
        ->call('deletePackage', $package->id)
        ->assertForbidden();
});

it('shows only packages without exit time', function () {
    // Arrange
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create(['person_id' => $person->id]);

    $activePackage = Package::factory()->create([
        'internal_person_id' => $internalPerson->id,
        'external_entity' => 'Active Package',
        'exit_time' => null
    ]);

    $completedPackage = Package::factory()->create([
        'internal_person_id' => $internalPerson->id,
        'external_entity' => 'Completed Package',
        'exit_time' => now()
    ]);

    // Act & Assert
    livewire(HomeTable::class)
        ->assertSee('Active Package')
        ->assertDontSee('Completed Package');
});