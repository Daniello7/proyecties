<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

it('loads the control access view when authenticated as porter', function () {
    // Arrange
    $user = User::factory()->create();
    $role = Role::create(['name' => 'porter']);
    $user->assignRole('porter');
    
    // Act
    $response = $this->actingAs($user)
        ->get(route('control-access'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('porter.home');
});

it('redirects to login when not authenticated', function () {
    // Act
    $response = $this->get(route('control-access'));

    // Assert
    $response->assertRedirect(route('login'));
});

it('returns 403 when authenticated but not porter', function () {
    // Arrange
    $user = User::factory()->create();
    
    // Act
    $response = $this->actingAs($user)
        ->get(route('control-access'));

    // Assert
    $response->assertForbidden();
});