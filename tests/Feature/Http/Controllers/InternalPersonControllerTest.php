<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function() {
    Role::create(['name' => 'porter']);
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'rrhh']);
});

it('loads the internal person index view as porter', function () {
    // Arrange
    $user = User::factory()->create();
    $user->assignRole('porter');

    // Act
    $response = $this->actingAs($user)
        ->get(route('internal-person'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('internal-person.index');
});

it('loads the internal person index view as admin', function () {
    // Arrange
    $user = User::factory()->create();
    $user->assignRole('admin');

    // Act
    $response = $this->actingAs($user)
        ->get(route('internal-person'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('internal-person.index');
});

it('loads the internal person index view as rrhh', function () {
    // Arrange
    $user = User::factory()->create();
    $user->assignRole('rrhh');

    // Act
    $response = $this->actingAs($user)
        ->get(route('internal-person'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('internal-person.index');
});

it('redirects to login when not authenticated', function () {
    // Act
    $response = $this->get(route('internal-person'));

    // Assert
    $response->assertRedirect(route('login'));
});

it('returns 403 when authenticated without required role', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = $this->actingAs($user)
        ->get(route('internal-person'));

    // Assert
    $response->assertForbidden();
});