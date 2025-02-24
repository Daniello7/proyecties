<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('can load the keys index page', function () {
    // Arrange
    Role::firstOrCreate(['name' => 'porter']);

    $user = User::factory()->create();
    $user->assignRole('porter');

    $this->actingAs($user);

    // Act
    $response = $this->get(route('keys.index'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('keys.index');
});
