<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function() {
    // Arrange
    Role::create(['name' => 'porter']);
    Role::create(['name' => 'some-other-role']);
    $this->user = User::factory()->create();
});

it('allows porter to access document exports page', function() {
    // Arrange
    $this->user->assignRole('porter');

    // Act
    $response = $this->actingAs($this->user)
        ->get(route('document-exports'));

    // Assert
    $response->assertOk()
        ->assertViewIs('document_exports.index');
});

it('prevents non-porter from accessing document exports page', function() {
    // Arrange
    $this->user->assignRole('some-other-role');

    // Act
    $response = $this->actingAs($this->user)
        ->get(route('document-exports'));

    // Assert
    $response->assertForbidden();
});

it('redirects unauthenticated users to login', function() {
    // Act
    $response = $this->get(route('document-exports'));

    // Assert
    $response->assertRedirect(route('login'));
});

it('returns correct view with expected data', function() {
    // Arrange
    $this->user->assignRole('porter');

    // Act
    $response = $this->actingAs($this->user)
        ->get(route('document-exports'));

    // Assert
    $response->assertOk()
        ->assertViewIs('document_exports.index')
        ->assertViewHas('errors', function($errors) {
            return $errors->isEmpty();
        });
});