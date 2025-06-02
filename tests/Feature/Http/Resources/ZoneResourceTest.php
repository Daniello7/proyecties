<?php

namespace Tests\Feature\Http\Resources;

use App\Http\Resources\ZoneResource;
use App\Models\Api\Zone;
use Illuminate\Http\Request;
use Mockery;

it('transforms zone data without schedule', function () {
    // Arrange
    $zone = Mockery::mock(Zone::class);
    $zone->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $zone->shouldReceive('getAttribute')->with('name')->andReturn('Zona Norte');
    $zone->shouldReceive('getAttribute')->with('location')->andReturn('Edificio A');
    $zone->shouldReceive('getAttribute')->with('pivot')->andReturn(null);
    $zone->shouldReceive('getRelation')->with('pivot')->andReturn(null);

    // Act
    $resource = new ZoneResource($zone);
    $result = $resource->toArray(Request::createFromGlobals());

    // Assert
    expect($result)
        ->toBeArray()
        ->toHaveKeys(['id', 'name', 'location'])
        ->and($result['id'])->toBe(1)
        ->and($result['name'])->toBe('Zona Norte')
        ->and($result['location'])->toBe('Edificio A');

});

it('transforms zone data with schedule', function () {
    // Arrange
    $pivot = new \stdClass();
    $pivot->schedule = 'L-V 9:00-18:00';

    $zone = Mockery::mock(Zone::class);
    $zone->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $zone->shouldReceive('getAttribute')->with('name')->andReturn('Zona Norte');
    $zone->shouldReceive('getAttribute')->with('location')->andReturn('Edificio A');
    $zone->shouldReceive('getAttribute')->with('pivot')->andReturn($pivot);
    $zone->shouldReceive('getRelation')->with('pivot')->andReturn(null);

    // Act
    $resource = new ZoneResource($zone);
    $result = $resource->toArray(Request::createFromGlobals());

    // Assert
    expect($result)
        ->toBeArray()
        ->toHaveKeys(['id', 'name', 'location', 'schedule'])
        ->and($result['id'])->toBe(1)
        ->and($result['name'])->toBe('Zona Norte')
        ->and($result['location'])->toBe('Edificio A')
        ->and($result['schedule'])->toBe('L-V 9:00-18:00');
});