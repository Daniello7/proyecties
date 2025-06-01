<?php

use App\Models\InternalPerson;
use App\Models\User;
use App\Models\Package;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::create(['name' => 'porter']);
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'rrhh']);
});

it('loads the packages index view as porter', function () {
    $user = User::factory()->create();
    $user->assignRole('porter');

    $response = $this->actingAs($user)
        ->get(route('packages'));

    $response->assertStatus(200);
    $response->assertViewIs('packages.index');
});

it('loads the packages index view as admin', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)
        ->get(route('packages'));

    $response->assertStatus(200);
    $response->assertViewIs('packages.index');
});

it('redirects to login when not authenticated', function () {
    $response = $this->get(route('packages'));

    $response->assertRedirect(route('login'));
});

it('returns 403 when authenticated but without required role', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('packages'));

    $response->assertForbidden();
});

it('returns 403 when authenticated as rrhh', function () {
    $user = User::factory()->create();
    $user->assignRole('rrhh');

    $response = $this->actingAs($user)
        ->get(route('packages'));

    $response->assertForbidden();
});
