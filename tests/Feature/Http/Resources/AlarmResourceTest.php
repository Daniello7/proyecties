<?php

namespace Tests\Feature\Http\Resources;

use App\Http\Resources\AlarmResource;
use App\Http\Resources\GuardResource;
use App\Http\Resources\ZoneResource;
use App\Models\Api\Alarm;
use App\Models\Api\Guard;
use App\Models\Api\Zone;
use Illuminate\Http\Request;
use Mockery;

beforeEach(function () {
    Request::createFromGlobals();
});

it('transforms alarm into correct format', function () {
    // Arrange
    $zone = Mockery::mock(Zone::class);
    $zone->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $zone->shouldReceive('setAttribute')->withAnyArgs()->andReturnSelf();

    $alarm = Mockery::mock(Alarm::class);
    $alarm->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $alarm->shouldReceive('getAttribute')->with('type')->andReturn('motion');
    $alarm->shouldReceive('getAttribute')->with('is_active')->andReturn(true);
    $alarm->shouldReceive('getAttribute')->with('description')->andReturn('Motion sensor');
    $alarm->shouldReceive('getAttribute')->with('zone')->andReturn($zone);
    $alarm->shouldReceive('setAttribute')->withAnyArgs()->andReturnSelf();
    $alarm->shouldReceive('relationLoaded')->with('assignedGuards')->andReturnFalse();
    $alarm->zone = $zone;

    // Act
    $resource = new AlarmResource($alarm);
    $result = $resource->toArray(Request::createFromGlobals());

    // Assert
    expect($result)->toBeArray()
        ->and($result)->toHaveKeys(['id', 'type', 'is_active', 'description', 'zone'])
        ->and($result['id'])->toBe(1)
        ->and($result['type'])->toBe('motion')
        ->and($result['is_active'])->toBeTrue()
        ->and($result['description'])->toBe('Motion sensor')
        ->and($result['zone'])->toBeInstanceOf(ZoneResource::class);
});

it('includes alarm triggers when relationship is loaded', function () {
    // Arrange
    $zone = Mockery::mock(Zone::class);
    $zone->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $zone->shouldReceive('setAttribute')->withAnyArgs()->andReturnSelf();

    $pivot = new \stdClass();
    $pivot->id = 1;
    $pivot->date = '2025-06-02';
    $pivot->is_false_alarm = false;

    $guard = Mockery::mock(Guard::class);
    $guard->shouldReceive('getAttribute')->with('notes')->andReturn('Test notes');
    $guard->shouldReceive('getAttribute')->with('pivot')->andReturn($pivot);
    $guard->shouldReceive('setAttribute')->withAnyArgs()->andReturnSelf();
    $guard->notes = 'Test notes';
    $guard->pivot = $pivot;

    $assignedGuards = collect([$guard]);

    $alarm = Mockery::mock(Alarm::class);
    $alarm->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $alarm->shouldReceive('getAttribute')->with('type')->andReturn('motion');
    $alarm->shouldReceive('getAttribute')->with('is_active')->andReturn(true);
    $alarm->shouldReceive('getAttribute')->with('description')->andReturn('Motion sensor');
    $alarm->shouldReceive('getAttribute')->with('zone')->andReturn($zone);
    $alarm->shouldReceive('getAttribute')->with('assignedGuards')->andReturn($assignedGuards);
    $alarm->shouldReceive('setAttribute')->withAnyArgs()->andReturnSelf();
    $alarm->shouldReceive('relationLoaded')->with('assignedGuards')->andReturnTrue();
    $alarm->zone = $zone;
    $alarm->assignedGuards = $assignedGuards;

    // Act
    $resource = new AlarmResource($alarm);
    $result = $resource->toArray(Request::createFromGlobals());

    // Assert
    expect($result)->toBeArray()
        ->and($result)->toHaveKeys(['id', 'type', 'is_active', 'description', 'zone', 'alarm_triggers_count', 'alarm_triggers'])
        ->and($result['alarm_triggers_count'])->toBe(1);
    
    // Verificamos que alarm_triggers sea un array después de convertirlo
    $triggers = collect($result['alarm_triggers'])->toArray();
    expect($triggers)->toBeArray()
        ->and($triggers)->toHaveCount(1);
    
    expect($triggers[0])->toHaveKeys(['alarm_trigger_id', 'date', 'is_false_alarm', 'notes', 'guard'])
        ->and($triggers[0]['alarm_trigger_id'])->toBe(1)
        ->and($triggers[0]['date'])->toBe('2025-06-02')
        ->and($triggers[0]['is_false_alarm'])->toBeFalse()
        ->and($triggers[0]['notes'])->toBe('Test notes')
        ->and($triggers[0]['guard'])->toBeInstanceOf(GuardResource::class);
});

it('does not include alarm triggers when relationship is not loaded', function () {
    // Arrange
    $zone = Mockery::mock(Zone::class);
    $zone->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $zone->shouldReceive('setAttribute')->withAnyArgs()->andReturnSelf();

    $alarm = Mockery::mock(Alarm::class);
    $alarm->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $alarm->shouldReceive('getAttribute')->with('type')->andReturn('motion');
    $alarm->shouldReceive('getAttribute')->with('is_active')->andReturn(true);
    $alarm->shouldReceive('getAttribute')->with('description')->andReturn('Motion sensor');
    $alarm->shouldReceive('getAttribute')->with('zone')->andReturn($zone);
    $alarm->shouldReceive('setAttribute')->withAnyArgs()->andReturnSelf();
    // Importante: cuando se usa whenLoaded(), Laravel verifica la relación de esta manera
    $alarm->shouldReceive('relationLoaded')->with('assignedGuards')->andReturnFalse();
    $alarm->zone = $zone;

    // Act
    $resource = new AlarmResource($alarm);
    $result = $resource->resolve(); // Usamos resolve() en lugar de toArray()

    // Assert
    expect($result)
        ->toBeArray()
        ->toHaveKeys(['id', 'type', 'is_active', 'description', 'zone'])
        ->and($result)->not->toHaveKey('alarm_triggers_count')
        ->and($result)->not->toHaveKey('alarm_triggers');
});