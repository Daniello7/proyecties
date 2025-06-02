<?php

namespace Tests\Feature\Api;

use App\Models\Api\Alarm;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user, [
        'read-alarms',
        'store-alarms',
        'update-alarms',
        'delete-alarms'
    ]);
});

it('can list all alarms', function () {
    // Arrange
    $alarms = Alarm::factory(3)->create();

    // Act
    $response = $this->getJson('/api/alarms');

    // Assert
    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

it('cannot list alarms without proper permission', function () {
    // Arrange
    Sanctum::actingAs($this->user, []);

    // Act
    $response = $this->getJson('/api/alarms');

    // Assert
    $response->assertForbidden();
});

it('can create an alarm', function () {
    // Arrange
    $alarmData = Alarm::factory()->raw();

    // Act
    $response = $this->postJson('/api/alarms', $alarmData);

    // Assert
    $response->assertCreated();
    $this->assertDatabaseHas('alarms', $alarmData);
});

it('cannot create alarm without proper permission', function () {
    // Arrange
    Sanctum::actingAs($this->user, []);
    $alarmData = Alarm::factory()->raw();

    // Act
    $response = $this->postJson('/api/alarms', $alarmData);

    // Assert
    $response->assertForbidden();
});

it('can show specific alarm', function () {
    // Arrange
    $alarm = Alarm::factory()->create();

    // Act
    $response = $this->getJson("/api/alarms/{$alarm->id}");

    // Assert
    $response->assertOk();
});

it('cannot show alarm without proper permission', function () {
    // Arrange
    Sanctum::actingAs($this->user, []);
    $alarm = Alarm::factory()->create();

    // Act
    $response = $this->getJson("/api/alarms/{$alarm->id}");

    // Assert
    $response->assertForbidden();
});

it('returns 404 when showing non-existent alarm', function () {
    // Act
    $response = $this->getJson('/api/alarms/999');

    // Assert
    $response->assertNotFound();
});

it('can update alarm', function () {
    // Arrange
    $alarm = Alarm::factory()->create();
    $updateData = Alarm::factory()->raw();

    // Act
    $response = $this->putJson("/api/alarms/{$alarm->id}", $updateData);

    // Assert
    $response->assertOk();
    $this->assertDatabaseHas('alarms', $updateData);
});

it('cannot update alarm without proper permission', function () {
    // Arrange
    Sanctum::actingAs($this->user, []);
    $alarm = Alarm::factory()->create();
    $updateData = Alarm::factory()->raw();

    // Act
    $response = $this->putJson("/api/alarms/{$alarm->id}", $updateData);

    // Assert
    $response->assertForbidden();
});

it('can delete alarm', function () {
    // Arrange
    $alarm = Alarm::factory()->create();

    // Act
    $response = $this->deleteJson("/api/alarms/{$alarm->id}");

    // Assert
    $response->assertOk();
    $this->assertDatabaseMissing('alarms', ['id' => $alarm->id]);
});

it('cannot delete alarm without proper permission', function () {
    // Arrange
    Sanctum::actingAs($this->user, []);
    $alarm = Alarm::factory()->create();

    // Act
    $response = $this->deleteJson("/api/alarms/{$alarm->id}");

    // Assert
    $response->assertForbidden();
});

it('returns 404 when deleting non-existent alarm', function () {
    // Act
    $response = $this->deleteJson('/api/alarms/999');

    // Assert
    $response->assertNotFound();
});

it('validates required fields when creating alarm', function () {
    // Act
    $response = $this->postJson('/api/alarms', []);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['zone_id', 'type', 'is_active']);
});

it('validates required fields when updating alarm', function () {
    // Arrange
    $alarm = Alarm::factory()->create();

    // Act
    $response = $this->putJson("/api/alarms/{$alarm->id}", []);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['zone_id', 'type', 'is_active']);
});