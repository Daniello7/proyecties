<?php

namespace Tests\Feature\Api;

use App\Models\Guard;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

uses(RefreshDatabase::class);

it('can list guards', function () {
    // Arrange
    $guard1 = Guard::factory()->create(['name' => 'Guard 1']);
    $guard2 = Guard::factory()->create(['name' => 'Guard 2']);

    // Act
    $response = $this->getJson(route('guards.index'));

    // Assert
    $response->assertStatus(Response::HTTP_OK);
    $response->assertJsonFragment(['name' => 'Guard 1']);
    $response->assertJsonFragment(['name' => 'Guard 2']);
});

it('can create a guard', function () {
    // Arrange
    $data = [
        'name' => 'New Guard',
        'dni' => '123456789',
    ];

    // Act
    $response = $this->postJson(route('guards.store'), $data);

    // Assert
    $response->assertCreated();
    $response->assertJsonFragment($data);
    $this->assertDatabaseHas('guards', $data);
});


it('can show a guard', function () {
    // Arrange
    $guard = Guard::factory()->create();

    // Act
    $response = $this->getJson(route('guards.show', $guard));

    // Assert
    $response->assertStatus(Response::HTTP_OK);
    $response->assertJsonFragment(['name' => $guard->name]);
});

it('returns 404 if guard not found', function () {
    // Act
    $response = $this->getJson(route('guards.show', 'nonexistent-id'));

    // Assert
    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

it('can update a guard', function () {
    // Arrange
    $guard = Guard::factory()->create();
    $data = [
        'name' => 'Updated Guard',
        'dni' => '987654321',
    ];

    // Act
    $response = $this->putJson(route('guards.update', $guard), $data);

    // Assert
    $response->assertNoContent();
    $this->assertDatabaseHas('guards', $data);
});

it('can delete a guard', function () {
    // Arrange
    $guard = Guard::factory()->create();

    // Act
    $response = $this->deleteJson(route('guards.destroy', $guard));

    // Assert
    $this->assertDatabaseMissing('guards', [
        'id' => $guard->id,
    ]);

    $response->assertStatus(204);
});

it('can assign a zone to a guard', function () {
    // Arrange
    $guard = Guard::factory()->create();
    $zone = Zone::factory()->create();
    $data = ['zone_id' => $zone->id, 'schedule' => '9:00 - 17:00'];

    // Act
    $response = $this->postJson(route('guards.assignZone', $guard), $data);

    // Assert
    $response->assertStatus(Response::HTTP_CREATED);
    $response->assertJsonFragment(['message' => 'Zone assigned with schedule']);
    $this->assertDatabaseHas('guard_zone', [
        'guard_id' => $guard->id,
        'zone_id' => $zone->id,
    ]);
});
