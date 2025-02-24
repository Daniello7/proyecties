<?php

use App\Http\Controllers\KeyControlController;
use App\Models\Person;
use App\Models\User;
use App\Models\Key;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

it('loads the key control index view', function () {
    // Arrange
    Route::get('/key-control', [KeyControlController::class, 'index'])->name('key-control.index');

    // Act
    $response = $this->get(route('key-control'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('key-control.index');
});

it('loads the key control create view', function () {
    // Arrange
    Route::get('/key-control/create', [KeyControlController::class, 'create'])->name('key-control.create');

    // Act
    $response = $this->get(route('key-control.create'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('key-control.create');
});

it('can store a new key control record', function () {
    // Arrange
    Role::firstOrCreate(['name' => 'porter']);

    $user = User::factory()->create();
    $user->assignRole('porter');

    $this->actingAs($user);
    $key = Key::factory()->create();
    $person = Person::factory()->create();

    $data = [
        'key_id' => $key->id,
        'person_id' => $person->id,
        'deliver_user_id' => $user->id,
        'receiver_user_id' => null,
        'exit_time' => now()->format("Y-m-d H:i:s"),
    ];

    // Act
    $response = $this->postJson(route('key-control.store'), $data);

    // Assert
    $response->assertRedirect(route('control-access'));
    $this->assertDatabaseHas('key_controls', $data);
});

