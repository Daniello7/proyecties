<?php

use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('generates a valid cleaning rules PDF', function () {
    actingAsPorter();
    $person = Person::factory()->create();

    $response = $this->get(route('cleaning-rules', ['person' => $person->id]));

    $response->assertHeader('Content-Type', 'application/pdf');
    $response->assertStatus(200);
});

it('generates a valid visitor rules PDF', function () {
    actingAsPorter();
    $person = Person::factory()->create();

    $response = $this->get(route('visitor-rules', ['person' => $person->id]));

    $response->assertHeader('Content-Type', 'application/pdf');
    $response->assertStatus(200);
});

it('generates a valid driver rules PDF', function () {
    actingAsPorter();
    $person = Person::factory()->create();

    $response = $this->get(route('driver-rules', ['person' => $person->id]));

    $response->assertHeader('Content-Type', 'application/pdf');
    $response->assertStatus(200);
});

it('returns 404 when person does not exist for cleaning rules', function () {
    actingAsPorter();

    $response = $this->get(route('cleaning-rules', ['person' => 999]));

    $response->assertStatus(404);
});

it('returns 404 when person does not exist for visitor rules', function () {
    actingAsPorter();

    $response = $this->get(route('visitor-rules', ['person' => 999]));

    $response->assertStatus(404);
});

it('returns 404 when person does not exist for driver rules', function () {
    actingAsPorter();

    $response = $this->get(route('driver-rules', ['person' => 999]));

    $response->assertStatus(404);
});

it('redirects to login when not authenticated for cleaning rules', function () {
    $person = Person::factory()->create();

    $response = $this->get(route('cleaning-rules', ['person' => $person->id]));

    $response->assertRedirect(route('login'));
});

it('redirects to login when not authenticated for visitor rules', function () {
    $person = Person::factory()->create();

    $response = $this->get(route('visitor-rules', ['person' => $person->id]));

    $response->assertRedirect(route('login'));
});

it('redirects to login when not authenticated for driver rules', function () {
    $person = Person::factory()->create();

    $response = $this->get(route('driver-rules', ['person' => $person->id]));

    $response->assertRedirect(route('login'));
});

it('returns 403 when user is not porter for cleaning rules', function () {
    $user = User::factory()->create();
    $person = Person::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('cleaning-rules', ['person' => $person->id]));

    $response->assertForbidden();
});

it('returns 403 when user is not porter for visitor rules', function () {
    $user = User::factory()->create();
    $person = Person::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('visitor-rules', ['person' => $person->id]));

    $response->assertForbidden();
});

it('returns 403 when user is not porter for driver rules', function () {
    $user = User::factory()->create();
    $person = Person::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('driver-rules', ['person' => $person->id]));

    $response->assertForbidden();
});