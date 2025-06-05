<?php

use App\Events\NotifyContactPackageEvent;
use App\Livewire\Packages\Index;
use App\Models\InternalPerson;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Spatie\Permission\Models\Role;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('shows package list by default', function () {
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    livewire(Index::class)
        ->assertSet('openedPackageList', true)
        ->assertSet('openedPackagesDeleted', false)
        ->assertSet('openedCreateReception', false)
        ->assertSet('openedCreateShipping', false);
});

it('opens package list correctly', function () {
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    livewire(Index::class)
        ->call('openPackageList')
        ->assertSet('openedPackageList', true)
        ->assertSet('openedPackagesDeleted', false)
        ->assertSet('openedCreateReception', false)
        ->assertSet('openedCreateShipping', false);
});

it('opens deleted packages correctly', function () {
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    livewire(Index::class)
        ->call('openPackagesDeleted')
        ->assertSet('openedPackageList', false)
        ->assertSet('openedPackagesDeleted', true)
        ->assertSet('openedCreateReception', false)
        ->assertSet('openedCreateShipping', false);
});

it('opens create reception form correctly', function () {
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    livewire(Index::class)
        ->call('openCreateReception')
        ->assertSet('openedPackageList', false)
        ->assertSet('openedPackagesDeleted', false)
        ->assertSet('openedCreateReception', true)
        ->assertSet('openedCreateShipping', false);
});

it('opens create shipping form correctly', function () {
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    livewire(Index::class)
        ->call('openCreateShipping')
        ->assertSet('openedPackageList', false)
        ->assertSet('openedPackagesDeleted', false)
        ->assertSet('openedCreateReception', false)
        ->assertSet('openedCreateShipping', true);
});

it('stores entry package correctly', function () {
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $internalPerson = InternalPerson::factory()->create();

    livewire(Index::class)
        ->set('agency', 'SEUR')
        ->set('external_entity', 'Test Entity')
        ->set('internal_person_id', $internalPerson->id)
        ->set('package_count', 1)
        ->set('comment', 'Test Comment')
        ->call('storePackage', 'entry')
        ->assertHasNoErrors()
        ->assertSet('openedPackageList', true);

    $this->assertDatabaseHas('packages', [
        'type' => 'entry',
        'agency' => 'SEUR',
        'external_entity' => 'Test Entity',
        'internal_person_id' => $internalPerson->id,
        'package_count' => 1,
        'comment' => 'Test Comment',
        'receiver_user_id' => $this->user->id
    ]);

});

it('stores exit package correctly', function () {
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $internalPerson = InternalPerson::factory()->create();

    livewire(Index::class)
        ->set('agency', 'SEUR')
        ->set('external_entity', 'Test Entity')
        ->set('internal_person_id', $internalPerson->id)
        ->set('package_count', 1)
        ->set('comment', 'Test Comment')
        ->call('storePackage', 'exit')
        ->assertHasNoErrors()
        ->assertSet('openedPackageList', true);

    $this->assertDatabaseHas('packages', [
        'type' => 'exit',
        'agency' => 'SEUR',
        'external_entity' => 'Test Entity',
        'internal_person_id' => $internalPerson->id,
        'package_count' => 1,
        'comment' => 'Test Comment',
        'receiver_user_id' => $this->user->id
    ]);
});

it('dispatches notification event when notify is true', function () {
    Event::fake();
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $internalPerson = InternalPerson::factory()->create();

    livewire(Index::class)
        ->set('agency', 'SEUR')
        ->set('external_entity', 'Test Entity')
        ->set('internal_person_id', $internalPerson->id)
        ->set('package_count', 1)
        ->set('notify', true)
        ->call('storePackage', 'entry');

    Event::assertDispatched(NotifyContactPackageEvent::class);
});

it('validates required fields when storing package', function () {
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    livewire(Index::class)
        ->call('storePackage', 'entry')
        ->assertHasErrors(['agency', 'external_entity', 'internal_person_id', 'package_count']);
});

it('prevents unauthorized package list view', function () {
    livewire(Index::class)
        ->call('openPackageList')
        ->assertForbidden();
});

it('prevents unauthorized package creation', function () {
    livewire(Index::class)
        ->call('openCreateReception')
        ->assertForbidden();
});

it('prevents unauthorized deleted packages view', function () {
    livewire(Index::class)
        ->call('openPackagesDeleted')
        ->assertForbidden();
});

it('prevents storing package with invalid type', function () {
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    livewire(Index::class)
        ->call('storePackage', 'invalid_type')
        ->assertStatus(404);
});