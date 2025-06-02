<?php

namespace Tests\Feature\Api;

use App\Models\Api\Zone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);
});

it('can list zones with read-zones permission', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create(), ['read-zones']);
    Zone::factory(3)->create();

    // Act
    $response = $this->getJson('/api/zones');

    // Assert
    $response->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'location'
                ]
            ]
        ]);
});

it('can list zones with read-own-zones permission', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create(), ['read-own-zones']);
    Zone::factory(3)->create();

    // Act
    $response = $this->getJson('/api/zones');

    // Assert
    $response->assertOk();
});

it('cannot list zones without proper permission', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create(), ['some-other-permission']);
    Zone::factory(3)->create();

    // Act
    $response = $this->getJson('/api/zones');

    // Assert
    $response->assertForbidden();
});

it('can create zone with store-zones permission', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create(), ['store-zones']);
    $zoneData = [
        'name' => 'Test Zone',
        'location' => 'Test Location'
    ];

    // Act
    $response = $this->postJson('/api/zones', $zoneData);

    // Assert
    $response->assertCreated()
        ->assertJsonFragment($zoneData);
    
    $this->assertDatabaseHas('zones', $zoneData);
});

it('cannot create zone without store-zones permission', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create(), ['some-other-permission']);
    $zoneData = [
        'name' => 'Test Zone',
        'location' => 'Test Location'
    ];

    // Act
    $response = $this->postJson('/api/zones', $zoneData);

    // Assert
    $response->assertForbidden();
    $this->assertDatabaseMissing('zones', $zoneData);
});

it('can show zone with read-zones permission', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create(), ['read-zones']);
    $zone = Zone::factory()->create();

    // Act
    $response = $this->getJson("/api/zones/{$zone->id}");

    // Assert
    $response->assertOk()
        ->assertJsonFragment([
            'id' => $zone->id,
            'name' => $zone->name,
            'location' => $zone->location
        ]);
});

it('cannot show zone without read-zones permission', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create(), ['some-other-permission']);
    $zone = Zone::factory()->create();

    // Act
    $response = $this->getJson("/api/zones/{$zone->id}");

    // Assert
    $response->assertForbidden();
});

it('can update zone with update-zones permission', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create(), ['update-zones']);
    $zone = Zone::factory()->create();
    $updateData = [
        'name' => 'Updated Zone',
        'location' => 'Updated Location'
    ];

    // Act
    $response = $this->putJson("/api/zones/{$zone->id}", $updateData);

    // Assert
    $response->assertOk()
        ->assertJsonFragment($updateData);
    
    $this->assertDatabaseHas('zones', array_merge(['id' => $zone->id], $updateData));
});

it('cannot update zone without update-zones permission', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create(), ['some-other-permission']);
    $zone = Zone::factory()->create();
    $originalData = $zone->toArray();
    $updateData = [
        'name' => 'Updated Zone',
        'location' => 'Updated Location'
    ];

    // Act
    $response = $this->putJson("/api/zones/{$zone->id}", $updateData);

    // Assert
    $response->assertForbidden();
    $this->assertDatabaseHas('zones', [
        'id' => $zone->id,
        'name' => $originalData['name'],
        'location' => $originalData['location']
    ]);
});

it('can delete zone with delete-zones permission', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create(), ['delete-zones']);
    $zone = Zone::factory()->create();

    // Act
    $response = $this->deleteJson("/api/zones/{$zone->id}");

    // Assert
    $response->assertOk();
    $this->assertDatabaseMissing('zones', ['id' => $zone->id]);
});

it('cannot delete zone without delete-zones permission', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create(), ['some-other-permission']);
    $zone = Zone::factory()->create();

    // Act
    $response = $this->deleteJson("/api/zones/{$zone->id}");

    // Assert
    $response->assertForbidden();
    $this->assertDatabaseHas('zones', ['id' => $zone->id]);
});

it('validates required fields when creating zone', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create(), ['store-zones']);

    // Act
    $response = $this->postJson('/api/zones', []);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'location']);
});

it('validates required fields when updating zone', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create(), ['update-zones']);
    $zone = Zone::factory()->create();

    // Act
    $response = $this->putJson("/api/zones/{$zone->id}", []);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'location']);
});

it('returns 404 for non-existent zone', function () {
    // Arrange
    Sanctum::actingAs(User::factory()->create(), ['read-zones']);
    $nonExistentId = Zone::max('id') + 1;

    // Act
    $response = $this->getJson("/api/zones/{$nonExistentId}");

    // Assert
    $response->assertNotFound();
});