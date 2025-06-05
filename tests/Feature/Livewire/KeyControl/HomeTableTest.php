<?php

use App\Livewire\KeyControl\HomeTable;
use App\Models\KeyControl;
use App\Models\User;
use App\Models\Key;
use App\Models\Person;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('shows only pending key controls', function () {
    // Arrange
    $keyPending = Key::factory()->create(['name' => 'Llave Pendiente']);
    $keyCompleted = Key::factory()->create(['name' => 'Llave Completada']);

    $pendingControl = KeyControl::factory()->create([
        'entry_time' => null,
        'key_id' => $keyPending->id
    ]);
    $completedControl = KeyControl::factory()->create([
        'entry_time' => now(),
        'key_id' => $keyCompleted->id
    ]);

    // Act & Assert
    livewire(HomeTable::class)
        ->assertSee('Llave Pendiente')
        ->assertDontSee('Llave Completada');
});

it('shows correct columns configuration', function () {
    // Arrange & Act
    $component = livewire(HomeTable::class)->instance();

    // Assert
    expect($component->columns)->toBe(['Person', 'Key', 'Comment', 'Actions'])
        ->and($component->select)->toContain('key_controls.id', 'key_controls.person_id', 'key_controls.key_id', 'key_controls.comment')
        ->and($component->columnMap)->toBe([
            'Key' => 'key.name',
            'Person' => 'person.name',
            'Comment' => null,
            'Actions' => null
        ]);
});

it('applies search filter correctly', function () {
    // Arrange
    $person1 = Person::factory()->create(['name' => 'Juan Test']);
    $person2 = Person::factory()->create(['name' => 'Pedro Test']);
    KeyControl::factory()->create(['person_id' => $person1->id, 'entry_time' => null]);
    KeyControl::factory()->create(['person_id' => $person2->id, 'entry_time' => null]);

    // Act & Assert
    livewire(HomeTable::class)
        ->set('search', 'Juan Test')
        ->assertSee('Juan Test')
        ->assertDontSee('Pedro Test');
});

it('sorts key controls by created_at in ascending order by default', function () {
    // Arrange
    $oldControl = KeyControl::factory()->create([
        'created_at' => now()->subDay(),
        'entry_time' => null
    ]);
    $newControl = KeyControl::factory()->create([
        'created_at' => now(),
        'entry_time' => null
    ]);

    // Act & Assert
    livewire(HomeTable::class)
        ->assertViewHas('rows', function ($rows) use ($oldControl) {
            return $rows->first()->id === $oldControl->id;
        });
});

it('changes sort direction when clicking same column', function () {
    // Arrange
    $key1 = Key::factory()->create(['name' => 'A Key']);
    $key2 = Key::factory()->create(['name' => 'B Key']);

    KeyControl::factory()->create([
        'key_id' => $key1->id,
        'entry_time' => null
    ]);
    $secondControl = KeyControl::factory()->create([
        'key_id' => $key2->id,
        'entry_time' => null
    ]);

    // Act & Assert
    livewire(HomeTable::class)
        ->call('sortBy', 'key.name')  // Primera vez: ordena ascendente
        ->call('sortBy', 'key.name')  // Segunda vez: cambia a descendente
        ->assertViewHas('rows', function ($rows) use ($secondControl) {
            return $rows->first()->id === $secondControl->id;
        });
});

it('updates key control reception successfully', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $keyControl = KeyControl::factory()->create([
        'entry_time' => null,
        'receiver_user_id' => null
    ]);
    Gate::define('update', fn($user, $model) => true);

    // Act & Assert
    livewire(HomeTable::class)
        ->call('updateKeyControlReception', $keyControl->id)
        ->assertHasNoErrors()
        ->assertSee(__('messages.key-control_updated'));

    // Assert
    $keyControl->refresh();

    expect($keyControl)
        ->entry_time->not->toBeNull()
        ->receiver_user_id->toBe($this->user->id);
});


it('prevents unauthorized key control updates', function () {
    // Arrange
    $keyControl = KeyControl::factory()->create(['entry_time' => null]);
    Gate::define('update', fn($user, $model) => false);

    // Act & Assert
    livewire(HomeTable::class)
        ->call('updateKeyControlReception', $keyControl->id)
        ->assertForbidden();
});

it('deletes key control record successfully', function () {
    // Arrange
    Role::create(['name' => 'porter']);
    $this->user->assignRole('porter');

    $keyControl = KeyControl::factory()->create([
        'entry_time' => null,
        'receiver_user_id' => null
    ]);
    Gate::define('cancel', fn($user, $model) => true);

    // Act & Assert
    livewire(HomeTable::class)
        ->call('deleteKeyControlRecord', $keyControl->id)
        ->assertHasNoErrors()
        ->assertSeeText(trans('messages.key-control_deleted'));

    $this->assertDatabaseMissing('key_controls', [
        'id' => $keyControl->id
    ]);
});

it('prevents unauthorized key control deletions', function () {
    // Arrange
    $keyControl = KeyControl::factory()->create(['entry_time' => null]);
    Gate::define('cancel', fn($user, $model) => false);

    // Act & Assert
    livewire(HomeTable::class)
        ->call('deleteKeyControlRecord', $keyControl->id)
        ->assertForbidden();
});

it('loads relations correctly', function () {
    // Arrange
    $person = Person::factory()->create(['name' => 'Test Person']);
    $key = Key::factory()->create(['name' => 'Test Key']);
    KeyControl::factory()->create([
        'entry_time' => null,
        'person_id' => $person->id,
        'key_id' => $key->id
    ]);

    // Act & Assert
    livewire(HomeTable::class)
        ->assertSee('Test Person')
        ->assertSee('Test Key');
});