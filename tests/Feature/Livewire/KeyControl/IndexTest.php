<?php

use App\Livewire\KeyControl\Index;
use App\Models\User;
use App\Models\Key;
use App\Models\Person;
use Spatie\Permission\Models\Role;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('shows correct initial state', function () {
    livewire(Index::class)
        ->assertSet('openedExitKeysTable', true)
        ->assertSet('openedCreateExitKey', false)
        ->assertSet('openedSearchKey', false)
        ->assertSet('openedKeyTable', false)
        ->assertSet('key_id', null)
        ->assertSet('person_id', null)
        ->assertSet('comment', '');
});

it('opens exit keys table correctly', function () {
    livewire(Index::class)
        ->call('openExitKeysTable')
        ->assertSet('openedExitKeysTable', true)
        ->assertSet('openedCreateExitKey', false)
        ->assertSet('openedSearchKey', false)
        ->assertSet('openedKeyTable', false);
});

it('opens create exit key correctly', function () {
    livewire(Index::class)
        ->call('openCreateExitKey')
        ->assertSet('openedExitKeysTable', false)
        ->assertSet('openedCreateExitKey', true)
        ->assertSet('openedSearchKey', false)
        ->assertSet('openedKeyTable', false);
});

it('opens search key correctly', function () {
    livewire(Index::class)
        ->call('openSearchKey')
        ->assertSet('openedExitKeysTable', false)
        ->assertSet('openedCreateExitKey', false)
        ->assertSet('openedSearchKey', true)
        ->assertSet('openedKeyTable', false);
});

it('opens key table correctly', function () {
    livewire(Index::class)
        ->call('openKeyTable')
        ->assertSet('openedExitKeysTable', false)
        ->assertSet('openedCreateExitKey', false)
        ->assertSet('openedSearchKey', false)
        ->assertSet('openedKeyTable', true);
});

it('resets key_id when area_id is updated', function () {
    livewire(Index::class)
        ->set('key_id', 1)
        ->set('areaId', 2)
        ->assertSet('key_id', null);
});

it('creates exit key control successfully', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $key = Key::factory()->create();
    $person = Person::factory()->create();

    // Act & Assert
    livewire(Index::class)
        ->set('key_id', $key->id)
        ->set('person_id', $person->id)
        ->set('comment', 'Test comment')
        ->call('storeExitKey')
        ->assertHasNoErrors()
        ->assertSet('key_id', null)
        ->assertSet('person_id', null)
        ->assertSet('comment', '')
        ->assertSeeText(__('messages.key-control_created'));

    $this->assertDatabaseHas('key_controls', [
        'key_id' => $key->id,
        'person_id' => $person->id,
        'comment' => 'Test comment',
        'deliver_user_id' => $this->user->id
    ]);
});

it('validates required fields for exit key creation', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    // Act & Assert
    livewire(Index::class)
        ->call('storeExitKey')
        ->assertHasErrors(['key_id', 'person_id']);
});

it('prevents unauthorized exit key creation', function () {
    // Arrange
    $key = Key::factory()->create();
    $person = Person::factory()->create();

    // Act & Assert
    livewire(Index::class)
        ->set('key_id', $key->id)
        ->set('person_id', $person->id)
        ->call('storeExitKey')
        ->assertForbidden();
});

it('validates key existence for exit key creation', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    // Act & Assert
    livewire(Index::class)
        ->set('key_id', 99999)
        ->set('person_id', 1)
        ->call('storeExitKey')
        ->assertHasErrors(['key_id']);
});

it('validates person existence for exit key creation', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');
    $key = Key::factory()->create();

    // Act & Assert
    livewire(Index::class)
        ->set('key_id', $key->id)
        ->set('person_id', 99999)
        ->call('storeExitKey')
        ->assertHasErrors(['person_id']);
});

it('validates comment length', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');
    $key = Key::factory()->create();
    $person = Person::factory()->create();

    // Act & Assert
    livewire(Index::class)
        ->set('key_id', $key->id)
        ->set('person_id', $person->id)
        ->set('comment', str_repeat('a', 256))
        ->call('storeExitKey')
        ->assertHasErrors(['comment']);
});