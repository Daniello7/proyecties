<?php

namespace Tests\Feature\Http\Resources;

use App\Http\Resources\GuardReportResource;
use App\Models\Api\Guard;
use App\Models\Api\GuardReport;
use App\Models\Api\Zone;
use Illuminate\Http\Request;
use Mockery;

it('correctly transforms guard report data with relationships', function () {
    // Arrange
    $zone = Mockery::mock(Zone::class);
    $zone->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $zone->shouldReceive('getAttribute')->with('name')->andReturn('Zona Norte');
    $zone->shouldReceive('getAttribute')->with('location')->andReturn('Edificio A');
    $zone->shouldReceive('setAttribute')->withAnyArgs()->andReturnSelf();

    $guard = Mockery::mock(Guard::class);
    $guard->shouldReceive('getAttribute')->with('dni')->andReturn('12345678X');
    $guard->shouldReceive('getAttribute')->with('name')->andReturn('Juan Guardia');
    $guard->shouldReceive('setAttribute')->withAnyArgs()->andReturnSelf();

    $guardReport = Mockery::mock(GuardReport::class);
    $guardReport->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $guardReport->shouldReceive('getAttribute')->with('guard_id')->andReturn(5);
    $guardReport->shouldReceive('getAttribute')->with('entry_time')->andReturn('2025-06-02 08:00:00');
    $guardReport->shouldReceive('getAttribute')->with('exit_time')->andReturn('2025-06-02 16:00:00');
    $guardReport->shouldReceive('getAttribute')->with('incident')->andReturn('Sin incidentes');
    $guardReport->shouldReceive('getAttribute')->with('zone')->andReturn($zone);
    $guardReport->shouldReceive('getAttribute')->with('assignedGuard')->andReturn($guard);
    $guardReport->shouldReceive('setAttribute')->withAnyArgs()->andReturnSelf();
    
    $guardReport->zone = $zone;
    $guardReport->assignedGuard = $guard;

    // Act
    $resource = new GuardReportResource($guardReport);
    $result = $resource->toArray(Request::createFromGlobals());

    // Assert
    expect($result)
        ->toBeArray()
        ->toHaveKeys(['id', 'zone', 'guard', 'entry_time', 'exit_time', 'incident'])
        ->and($result['zone'])->toBeArray()
        ->and($result['zone'])->toHaveKeys(['id', 'name', 'location'])
        ->and($result['zone']['id'])->toBe(1)
        ->and($result['zone']['name'])->toBe('Zona Norte')
        ->and($result['zone']['location'])->toBe('Edificio A')
        ->and($result['guard'])->toBeArray()
        ->and($result['guard'])->toHaveKeys(['id', 'dni', 'name'])
        ->and($result['guard']['id'])->toBe(5)
        ->and($result['guard']['dni'])->toBe('12345678X')
        ->and($result['guard']['name'])->toBe('Juan Guardia')
        ->and($result['entry_time'])->toBe('2025-06-02 08:00:00')
        ->and($result['exit_time'])->toBe('2025-06-02 16:00:00')
        ->and($result['incident'])->toBe('Sin incidentes');
});