<?php

use App\Models\User;
use App\Models\InternalPerson;
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

// ... (mantener los tests existentes del index) ...

it('loads the internal person show view as admin', function () {
    // Arrange
    $user = User::factory()->create();
    $user->assignRole('admin');
    $internalPerson = InternalPerson::factory()->create();

    // Act
    $response = $this->actingAs($user)
        ->get(route('internal-person.show', $internalPerson->id));

    // Assert
    $response->assertOk()
        ->assertViewIs('internal-person.show')
        ->assertViewHas('internalPerson', $internalPerson);
});

it('loads the internal person show view as rrhh', function () {
    // Arrange
    $user = User::factory()->create();
    $user->assignRole('rrhh');
    $internalPerson = InternalPerson::factory()->create();

    // Act
    $response = $this->actingAs($user)
        ->get(route('internal-person.show', $internalPerson->id));

    // Assert
    $response->assertOk()
        ->assertViewIs('internal-person.show')
        ->assertViewHas('internalPerson', $internalPerson);
});

it('prevents porter from viewing internal person details', function () {
    // Arrange
    $user = User::factory()->create();
    $user->assignRole('porter');
    $internalPerson = InternalPerson::factory()->create();

    // Act
    $response = $this->actingAs($user)
        ->get(route('internal-person.show', $internalPerson->id));

    // Assert
    $response->assertForbidden();
});

it('returns 404 when internal person does not exist for admin', function () {
    // Arrange
    $user = User::factory()->create();
    $user->assignRole('admin');

    // Act
    $response = $this->actingAs($user)
        ->get(route('internal-person.show', 999));

    // Assert
    $response->assertNotFound();
});

it('returns 403 when trying to show internal person without required role', function () {
    // Arrange
    $user = User::factory()->create();
    $internalPerson = InternalPerson::factory()->create();

    // Act
    $response = $this->actingAs($user)
        ->get(route('internal-person.show', $internalPerson->id));

    // Assert
    $response->assertForbidden();
});

it('redirects to login when trying to show internal person while not authenticated', function () {
    // Arrange
    $internalPerson = InternalPerson::factory()->create();

    // Act
    $response = $this->get(route('internal-person.show', $internalPerson->id));

    // Assert
    $response->assertRedirect(route('login'));
});