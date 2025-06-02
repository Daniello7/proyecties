<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Api\Alarm;
use App\Models\Api\Guard;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can attach guard to alarm', function () {
    // Arrange
    Sanctum::actingAs($this->user, ['attach-alarm-guard']);
    $alarm = Alarm::factory()->create();
    $guard = Guard::factory()->create();
    
    $data = [
        'alarm_id' => $alarm->id,
        'guard_id' => $guard->id,
        'is_false_alarm' => false,
        'notes' => 'Test notes'
    ];

    // Act
    $response = $this->postJson('/api/alarm/attach-guard', $data);

    // Assert
    $response->assertOk();
    $this->assertDatabaseHas('alarm_guard', [
        'alarm_id' => $alarm->id,
        'guard_id' => $guard->id,
        'is_false_alarm' => false,
        'notes' => 'Test notes'
    ]);
});

it('cannot attach guard to alarm without proper permission', function () {
    // Arrange
    Sanctum::actingAs($this->user, []); // Sin permisos
    $alarm = Alarm::factory()->create();
    $guard = Guard::factory()->create();
    
    $data = [
        'alarm_id' => $alarm->id,
        'guard_id' => $guard->id,
        'is_false_alarm' => false,
        'notes' => 'Test notes'
    ];

    // Act
    $response = $this->postJson('/api/alarm/attach-guard', $data);

    // Assert
    $response->assertForbidden();
});

it('validates required fields when attaching guard to alarm', function () {
    // Arrange
    Sanctum::actingAs($this->user, ['attach-alarm-guard']);

    // Act
    $response = $this->postJson('/api/alarm/attach-guard', []);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['alarm_id', 'guard_id', 'is_false_alarm']);
});

it('validates alarm existence when attaching guard', function () {
    // Arrange
    Sanctum::actingAs($this->user, ['attach-alarm-guard']);
    $guard = Guard::factory()->create();
    
    $data = [
        'alarm_id' => 999,
        'guard_id' => $guard->id,
        'is_false_alarm' => false
    ];

    // Act
    $response = $this->postJson('/api/alarm/attach-guard', $data);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['alarm_id']);
});

it('validates guard existence when attaching to alarm', function () {
    // Arrange
    Sanctum::actingAs($this->user, ['attach-alarm-guard']);
    $alarm = Alarm::factory()->create();
    
    $data = [
        'alarm_id' => $alarm->id,
        'guard_id' => 999,
        'is_false_alarm' => false
    ];

    // Act
    $response = $this->postJson('/api/alarm/attach-guard', $data);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['guard_id']);
});

it('validates is_false_alarm is boolean', function () {
    // Arrange
    Sanctum::actingAs($this->user, ['attach-alarm-guard']);
    $alarm = Alarm::factory()->create();
    $guard = Guard::factory()->create();
    
    $data = [
        'alarm_id' => $alarm->id,
        'guard_id' => $guard->id,
        'is_false_alarm' => 'not-a-boolean'
    ];

    // Act
    $response = $this->postJson('/api/alarm/attach-guard', $data);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['is_false_alarm']);
});

it('validates notes max length', function () {
    // Arrange
    Sanctum::actingAs($this->user, ['attach-alarm-guard']);
    $alarm = Alarm::factory()->create();
    $guard = Guard::factory()->create();
    
    $data = [
        'alarm_id' => $alarm->id,
        'guard_id' => $guard->id,
        'is_false_alarm' => false,
        'notes' => str_repeat('a', 256)
    ];

    // Act
    $response = $this->postJson('/api/alarm/attach-guard', $data);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['notes']);
});