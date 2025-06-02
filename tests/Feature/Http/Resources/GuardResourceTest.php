<?php

namespace Tests\Feature\Http\Resources;

use App\Http\Resources\GuardResource;
use App\Models\Api\Guard;
use App\Models\Api\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mockery;

it('transforms guard data without zones when zones are not loaded', function () {
    // Arrange
    $guard = Mockery::mock(Guard::class);
    $guard->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $guard->shouldReceive('getAttribute')->with('dni')->andReturn('12345678X');
    $guard->shouldReceive('getAttribute')->with('name')->andReturn('Juan Guardia');
    $guard->shouldReceive('relationLoaded')->with('zones')->andReturnFalse();

    // Act
    $resource = new GuardResource($guard);
    $result = $resource->toArray(Request::createFromGlobals());

    // Assert
    expect($result)
        ->toBeArray()
        ->toHaveKeys(['id', 'dni', 'name'])
        ->and($result['id'])->toBe(1)
        ->and($result['dni'])->toBe('12345678X')
        ->and($result['name'])->toBe('Juan Guardia');
});

it('transforms guard data with zones when zones are loaded', function () {
    // Arrange
    $zone1 = Mockery::mock(Zone::class);
    $zone1->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $zone1->shouldReceive('getAttribute')->with('name')->andReturn('Zona Norte');
    $zone1->shouldReceive('getAttribute')->with('location')->andReturn('Edificio A');

    $zone2 = Mockery::mock(Zone::class);
    $zone2->shouldReceive('getAttribute')->with('id')->andReturn(2);
    $zone2->shouldReceive('getAttribute')->with('name')->andReturn('Zona Sur');
    $zone2->shouldReceive('getAttribute')->with('location')->andReturn('Edificio B');

    $zones = new Collection([$zone1, $zone2]);

    $guard = Mockery::mock(Guard::class);
    $guard->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $guard->shouldReceive('getAttribute')->with('dni')->andReturn('12345678X');
    $guard->shouldReceive('getAttribute')->with('name')->andReturn('Juan Guardia');
    $guard->shouldReceive('relationLoaded')->with('zones')->andReturnTrue();
    $guard->shouldReceive('getAttribute')->with('zones')->andReturn($zones);

    // Act
    $resource = new GuardResource($guard);
    $result = $resource->toArray(Request::createFromGlobals());

    // Assert
    expect($result)
        ->toBeArray()
        ->toHaveKeys(['id', 'dni', 'name', 'zones'])
        ->and($result['id'])->toBe(1)
        ->and($result['dni'])->toBe('12345678X')
        ->and($result['name'])->toBe('Juan Guardia')
        ->and(count($result['zones']))->toBe(2);
});