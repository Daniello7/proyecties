<?php

use App\Models\Api\Alarm;
use App\Models\Api\Guard;
use App\Models\Api\Zone;

it('belongs to a zone', function () {
    // Arrange
    $zone = Zone::factory()->create();
    $alarm = Alarm::factory()->create(['zone_id' => $zone->id]);

    // Assert
    expect($alarm->zone)
        ->toBeInstanceOf(Zone::class)
        ->id->toBe($zone->id);
});

it('can have many assigned guards with pivot data', function () {
    // Arrange
    $alarm = Alarm::factory()->create();
    $guards = Guard::factory(3)->create();
    $pivotData = [
        'date' => now(),
        'is_false_alarm' => true,
        'notes' => 'Test notes'
    ];

    // Act
    $alarm->assignedGuards()->attach($guards, $pivotData);
    $alarm->refresh();

    // Assert
    expect($alarm->assignedGuards)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(Guard::class);

    $pivot = $alarm->assignedGuards->first()->pivot;

    expect($pivot)
        ->date->not->toBeNull()
        ->and($pivot->is_false_alarm)->toBe(1)
        ->and($pivot->notes)->toBe('Test notes')
        ->and($pivot->created_at)->not->toBeNull()
        ->and($pivot->updated_at)->not->toBeNull();
});

it('includes assigned guards when show_triggers is true', function () {
    // Arrange
    actingAsAdmin();

    $alarm = Alarm::factory()->create();
    $guards = Guard::factory(2)->create();
    $pivotData = [
        'date' => now(),
        'is_false_alarm' => false,
        'notes' => null
    ];
    $alarm->assignedGuards()->attach($guards, $pivotData);

    // Act & Assert
    $response = $this->getJson('/api/alarms?show_triggers=true')
        ->assertOk();

    expect($response->json('data.0.alarm_triggers'))
        ->toBeArray()
        ->toHaveCount(2);

    // Verificamos también el contador
    expect($response->json('data.0.alarm_triggers_count'))->toBe(2);
});

it('does not include assigned guards when show_triggers is false', function () {
    // Arrange
    actingAsAdmin();

    $alarm = Alarm::factory()->create();
    $guards = Guard::factory(2)->create();
    $pivotData = [
        'date' => now(),
        'is_false_alarm' => false,
        'notes' => null
    ];
    $alarm->assignedGuards()->attach($guards, $pivotData);

    // Act & Assert
    $this->getJson('/api/alarms?show_triggers=false')
        ->assertOk()
        ->assertJsonMissing(['assigned_guards']);
});

it('does not include assigned guards when show_triggers is not provided', function () {
    // Arrange
    actingAsAdmin();

    $alarm = Alarm::factory()->create();
    $guards = Guard::factory(2)->create();
    $pivotData = [
        'date' => now(),
        'is_false_alarm' => false,
        'notes' => null
    ];
    $alarm->assignedGuards()->attach($guards, $pivotData);

    // Act & Assert
    $this->getJson('/api/alarms')
        ->assertOk()
        ->assertJsonMissing(['assigned_guards']);
});
