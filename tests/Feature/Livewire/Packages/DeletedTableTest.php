<?php

use App\Livewire\Packages\DeletedTable;
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

it('shows empty recycle bin message when no deleted packages exist', function () {
    livewire(DeletedTable::class)
        ->assertSee(__('Recycle bin is empty'))
        ->assertDontSee('confirm-delete-all');
});

it('displays deleted packages correctly', function () {
    // Arrange
    $receiver = User::factory()->create(['name' => 'Test Receiver']);
    $person = Person::factory()->create(['name' => 'Test Person']);
    $internalPerson = InternalPerson::factory()->create(['person_id' => $person->id]);

    $package = Package::factory()->create([
        'internal_person_id' => $internalPerson->id,
        'receiver_user_id' => $receiver->id,
        'type' => 'entry',
        'agency' => 'SEUR',
        'external_entity' => 'Test Entity',
        'package_count' => 1
    ]);

    $package->delete();

    // Act & Assert
    livewire(DeletedTable::class)
        ->assertSee(__('Entry'))
        ->assertSee('SEUR')
        ->assertSee('Test Entity')
        ->assertSee('Test Person')
        ->assertSee('Test Receiver')
        ->assertDontSee(__('Recycle bin is empty'));
});

it('filters deleted packages by search term', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();
    $receiver = User::factory()->create();

    $package1 = Package::factory()->create([
        'internal_person_id' => $internalPerson->id,
        'receiver_user_id' => $receiver->id,
        'external_entity' => 'Unique Package Name'
    ]);

    $package2 = Package::factory()->create([
        'internal_person_id' => $internalPerson->id,
        'receiver_user_id' => $receiver->id,
        'external_entity' => 'Different Package'
    ]);

    $package1->delete();
    $package2->delete();

    // Act & Assert
    livewire(DeletedTable::class)
        ->set('search', 'Unique')
        ->assertSee('Unique Package Name')
        ->assertDontSee('Different Package');
});

it('restores package successfully', function () {
    // Arrange
    Role::create(['name' => 'admin']);
    $this->user->assignRole('admin');

    $package = Package::factory()->create();
    $package->delete();

    // Act & Assert
    livewire(DeletedTable::class)
        ->call('restore', $package->id)
        ->assertSet('activeModal', null)
        ->assertSee(__('messages.package_restored'));

    $this->assertNotSoftDeleted('packages', ['id' => $package->id]);
});

it('permanently deletes package successfully', function () {
    // Arrange
    Role::create(['name' => 'admin']);
    $this->user->assignRole('admin');

    $package = Package::factory()->create();
    $package->delete();

    // Act & Assert
    livewire(DeletedTable::class)
        ->call('openModal', 'confirm-delete', $package->id)
        ->assertSet('package_id', $package->id)
        ->call('deletePackage', $package->id)
        ->assertSet('activeModal', null)
        ->assertSee(__('messages.package_deleted'));

    $this->assertDatabaseMissing('packages', ['id' => $package->id]);
});

it('permanently deletes all packages successfully', function () {
    // Arrange
    Role::create(['name' => 'admin']);
    $this->user->assignRole('admin');

    $receiver = User::factory()->create();
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create(['person_id' => $person->id]);

    $packages = Package::factory(3)->create([
        'internal_person_id' => $internalPerson->id,
        'receiver_user_id' => $receiver->id
    ]);

    foreach ($packages as $package) {
        $package->delete();
    }

    // Act & Assert
    livewire(DeletedTable::class)
        ->call('openModal', 'confirm-delete-all')
        ->call('deleteAllPackages')
        ->assertSet('activeModal', null)
        ->assertSee(__('messages.package_deleted'))
        ->assertSee(__('Recycle bin is empty'));

    foreach ($packages as $package) {
        $this->assertDatabaseMissing('packages', ['id' => $package->id]);
    }
});

it('prevents unauthorized package restore', function () {
    $package = Package::factory()->create();
    $package->delete();

    livewire(DeletedTable::class)
        ->call('restore', $package->id)
        ->assertForbidden();
});

it('prevents unauthorized package permanent deletion', function () {
    $package = Package::factory()->create();
    $package->delete();

    livewire(DeletedTable::class)
        ->call('deletePackage', $package->id)
        ->assertForbidden();
});

it('prevents unauthorized all packages permanent deletion', function () {
    livewire(DeletedTable::class)
        ->call('deleteAllPackages')
        ->assertForbidden();
});

it('sorts packages by deleted date in descending order by default', function () {
    // Arrange
    $packages = Package::factory(3)->create();
    foreach ($packages as $package) {
        $package->delete();
    }

    // Act & Assert
    $component = livewire(DeletedTable::class)->instance();

    expect($component)
        ->sortColumn->toBe('deleted_at')
        ->sortDirection->toBe('desc');
});

it('closes modal correctly', function () {
    livewire(DeletedTable::class)
        ->set('activeModal', 'confirm-delete')
        ->call('closeModal')
        ->assertSet('activeModal', null);
});

it('paginates results correctly', function () {
    // Arrange
    $receiver = User::factory()->create();
    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create(['person_id' => $person->id]);

    $packages = [];
    for ($i = 0; $i < 51; $i++) {
        $packages[] = Package::factory()->create([
            'internal_person_id' => $internalPerson->id,
            'receiver_user_id' => $receiver->id
        ]);
    }

    foreach ($packages as $package) {
        $package->delete();
    }

    // Act & Assert
    livewire(DeletedTable::class)
        ->assertViewHas('rows', fn($rows) => $rows instanceof \Illuminate\Pagination\LengthAwarePaginator
            && $rows->count() === 50
        );
});