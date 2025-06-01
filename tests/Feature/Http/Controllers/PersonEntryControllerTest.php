<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'porter']);
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'rrhh']);
});

it('loads the person entries index view as porter', function () {
    $user = User::factory()->create();
    $user->assignRole('porter');
    
    $response = $this->actingAs($user)
        ->get(route('person-entries'));

    $response->assertStatus(200);
    $response->assertViewIs('person-entry.index');
});

it('loads the person entries index view as admin', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    
    $response = $this->actingAs($user)
        ->get(route('person-entries'));

    $response->assertStatus(200);
    $response->assertViewIs('person-entry.index');
});

it('loads the person entries index view as rrhh', function () {
    $user = User::factory()->create();
    $user->assignRole('rrhh');
    
    $response = $this->actingAs($user)
        ->get(route('person-entries'));

    $response->assertStatus(200);
    $response->assertViewIs('person-entry.index');
});

it('redirects to login when not authenticated', function () {
    $response = $this->get(route('person-entries'));

    $response->assertRedirect(route('login'));
});

it('returns 403 when authenticated but without required role', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->get(route('person-entries'));

    $response->assertForbidden();
});