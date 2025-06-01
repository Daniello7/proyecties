<?php

use App\Models\Person;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'porter']);
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'rrhh']);
});

it('shows a person successfully as porter', function () {
    $user = User::factory()->create();
    $user->assignRole('porter');
    $person = Person::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('person.show', $person->id));

    $response->assertStatus(200);
    $response->assertViewIs('person.show');
    $response->assertViewHas('person', $person);
});

it('shows a person successfully as admin', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $person = Person::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('person.show', $person->id));

    $response->assertStatus(200);
    $response->assertViewIs('person.show');
    $response->assertViewHas('person', $person);
});

it('shows a person successfully as rrhh', function () {
    $user = User::factory()->create();
    $user->assignRole('rrhh');
    $person = Person::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('person.show', $person->id));

    $response->assertStatus(200);
    $response->assertViewIs('person.show');
    $response->assertViewHas('person', $person);
});

it('returns 404 if person does not exist', function () {
    $user = User::factory()->create();
    $user->assignRole('porter');

    $response = $this->actingAs($user)
        ->get(route('person.show', 999));

    $response->assertStatus(404);
});

it('redirects to login when not authenticated', function () {
    $person = Person::factory()->create();

    $response = $this->get(route('person.show', $person->id));

    $response->assertRedirect(route('login'));
});

it('returns 403 when user has no required role', function () {
    $user = User::factory()->create();
    $person = Person::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('person.show', $person->id));

    $response->assertForbidden();
});