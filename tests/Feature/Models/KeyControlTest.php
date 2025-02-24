<?php

use App\Models\KeyControl;
use App\Models\Key;
use App\Models\Person;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->key = Key::factory()->create();
    $this->person = Person::factory()->create();
});

it('creates a new key control record', function () {

    // Act
    $keyControl = KeyControl::create([
        'key_id' => $this->key->id,
        'person_id' => $this->person->id,
        'deliver_user_id' => $this->user->id,
        'receiver_user_id' => $this->user->id,
        'comment' => 'Test comment',
        'exit_time' => now(),
    ]);

    // Assert
    expect($keyControl)->toBeInstanceOf(KeyControl::class)
        ->and($keyControl->key_id)->toBe($this->key->id)
        ->and($keyControl->person_id)->toBe($this->person->id)
        ->and($keyControl->comment)->toBe('Test comment')
        ->and($keyControl->exit_time)->toBeInstanceOf(Carbon::class);
});

it('fails to create a key control record without required fields', function () {
    // Act & Assert
    expect(fn() => KeyControl::create([
    ]))->toThrow(QueryException::class);
});

it('updates a key control record', function () {
    // Arrange
    $keyControl = KeyControl::factory()->create();

    // Act
    $keyControl->update([
        'comment' => 'Updated comment',
    ]);

    // Assert
    expect($keyControl->fresh()->comment)->toBe('Updated comment');
});

it('deletes a key control record', function () {
    // Arrange
    $keyControl = KeyControl::factory()->create();

    // Act
    $keyControl->delete();

    // Assert
    expect($keyControl->exists)->toBeFalse();
});
