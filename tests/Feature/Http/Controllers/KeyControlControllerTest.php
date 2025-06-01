<?php

use App\Http\Controllers\KeyControlController;
use App\Models\Person;
use App\Models\User;
use App\Models\Key;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::create(['name' => 'porter']);
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'rrhh']);
});

it('loads the key control index view as porter', function () {
    // Arrange
    $user = User::factory()->create();
    $user->assignRole('porter');

    // Act
    $response = $this->actingAs($user)
        ->get(route('key-control'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('key-control.index');
});

it('loads the key control index view as admin', function () {
    // Arrange
    $user = User::factory()->create();
    $user->assignRole('admin');

    // Act
    $response = $this->actingAs($user)
        ->get(route('key-control'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('key-control.index');
});

it('redirects to login when not authenticated', function () {
    // Act
    $response = $this->get(route('key-control'));

    // Assert
    $response->assertRedirect(route('login'));
});

it('returns 403 when authenticated without required role', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = $this->actingAs($user)
        ->get(route('key-control'));

    // Assert
    $response->assertForbidden();
});

it('returns 403 when authenticated as rrhh', function () {
    // Arrange
    $user = User::factory()->create();
    $user->assignRole('rrhh');

    // Act
    $response = $this->actingAs($user)
        ->get(route('key-control'));

    // Assert
    $response->assertForbidden();
});
