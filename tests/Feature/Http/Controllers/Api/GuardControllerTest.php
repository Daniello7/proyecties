<?php

namespace Tests\Feature\Api;

use App\Models\Api\Guard;
use App\Models\Api\Zone;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

uses(RefreshDatabase::class);

beforeEach(function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);
});

// El resto de los tests permanecen igual, excepto el último

it('can list guards', function () {
    // Arrange
    Guard::factory()->create(['name' => 'Guard 1']);
    Guard::factory()->create(['name' => 'Guard 2']);

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
    $response = $this->getJson(route('guards.show', Guard::max('id') + 1));

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
    $response->assertOk();
    $response->assertJsonFragment($data);
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
    
    $response->assertOk();
    $response->assertJsonFragment([
        'id' => $guard->id,
    ]);
});

it('can assign a zone to a guard', function () {
    // Arrange
    $guard = Guard::factory()->create();
    $zone = Zone::factory()->create();
    $data = ['zone_id' => $zone->id, 'schedule' => '9:00 - 17:00'];

    // Act
    $response = $this->postJson('/api/guards/attach-zone', [
        'guard_id' => $guard->id,
        'zone_id' => $zone->id,
        'schedule' => $data['schedule']
    ]);

    // Assert
    $response->assertStatus(Response::HTTP_OK);
    $this->assertDatabaseHas('guard_zone', [
        'guard_id' => $guard->id,
        'zone_id' => $zone->id,
    ]);
});

it('can detach a zone from a guard', function () {
    // Arrange
    $guard = Guard::factory()->create();
    $zone = Zone::factory()->create();
    $guard->zones()->attach($zone->id, ['schedule' => '9:00 - 17:00']);

    // Act
    $response = $this->postJson('/api/guards/detach-zone', [
        'guard_id' => $guard->id,
        'zone_id' => $zone->id
    ]);

    // Assert
    $response->assertOk();
    $this->assertDatabaseMissing('guard_zone', [
        'guard_id' => $guard->id,
        'zone_id' => $zone->id,
    ]);
});

it('returns 403 when trying to attach zone without permission', function () {
    // Arrange
    $guard = Guard::factory()->create();
    $zone = Zone::factory()->create();
    Sanctum::actingAs(User::factory()->create(), []);

    // Act
    $response = $this->postJson('/api/guards/attach-zone', [
        'guard_id' => $guard->id,
        'zone_id' => $zone->id,
        'schedule' => '9:00 - 17:00'
    ]);

    // Assert
    $response->assertForbidden();
});

it('returns 404 when attaching zone to non-existent guard', function () {
    // Arrange
    $zone = Zone::factory()->create();
    $nonExistentGuardId = Guard::max('id') + 1;

    // Act
    $response = $this->postJson('/api/guards/attach-zone', [
        'guard_id' => $nonExistentGuardId,
        'zone_id' => $zone->id,
        'schedule' => '9:00 - 17:00'
    ]);

    // Assert
    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['guard_id']);
});

it('returns 404 when attaching non-existent zone to guard', function () {
    // Arrange
    $guard = Guard::factory()->create();
    $nonExistentZoneId = Zone::max('id') + 1;

    // Act
    $response = $this->postJson('/api/guards/attach-zone', [
        'guard_id' => $guard->id,
        'zone_id' => $nonExistentZoneId,
        'schedule' => '9:00 - 17:00'
    ]);

    // Assert
    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['zone_id']);

});

it('returns 400 when trying to detach non-assigned zone', function () {
    // Arrange
    $guard = Guard::factory()->create();
    $zone = Zone::factory()->create();

    // Act
    $response = $this->postJson('/api/guards/detach-zone', [
        'guard_id' => $guard->id,
        'zone_id' => $zone->id
    ]);

    // Assert
    $response->assertStatus(400);
});