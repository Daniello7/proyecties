<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function() {
    Role::create(['name' => 'rrhh']);
    $this->user = User::factory()->create();
});

it('allows human resources to access HR page', function() {
    // Arrange
    $this->user->assignRole('rrhh');

    // Act
    $response = $this->actingAs($this->user)
        ->get(route('human-resources'));

    // Assert
    $response->assertOk()
        ->assertViewIs('hr.index');
});

it('prevents non-hr staff from accessing HR page', function() {
    // Act
    $response = $this->actingAs($this->user)
        ->get(route('human-resources'));

    // Assert
    $response->assertForbidden();
});

it('redirects unauthenticated users to login', function() {
    // Act
    $response = $this->get(route('human-resources'));

    // Assert
    $response->assertRedirect(route('login'));
});