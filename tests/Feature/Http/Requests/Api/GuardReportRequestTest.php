<?php

use App\Http\Requests\Api\GuardReportRequest;
use Illuminate\Support\Facades\Route;
use App\Models\Api\Guard;
use App\Models\Api\Zone;

beforeEach(function () {
    Route::post('/test-guard-report', function (GuardReportRequest $request) {
        return response()->json(['success' => true]);
    });
});

it('authorizes all users to make the request', function () {
    $request = new GuardReportRequest();
    
    expect($request->authorize())->toBeTrue();
});

it('validates request with valid data', function () {
    $guard = Guard::factory()->create();
    $zone = Zone::factory()->create();
    
    $validData = [
        'guard_id' => $guard->id,
        'zone_id' => $zone->id,
        'entry_time' => '2025-06-01 07:00:00',
        'exit_time' => '2025-06-01 19:00:00',
        'incident' => 'Test incident'
    ];

    $response = $this->postJson('/test-guard-report', $validData);

    $response->assertStatus(200);
});

it('validates guard_id exists', function () {
    $zone = Zone::factory()->create();
    
    $invalidData = [
        'guard_id' => 999999,
        'zone_id' => $zone->id,
        'entry_time' => '2025-06-01 07:00:00',
        'exit_time' => '2025-06-01 19:00:00'
    ];

    $response = $this->postJson('/test-guard-report', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['guard_id']);
});

it('validates zone_id exists', function () {
    $guard = Guard::factory()->create();
    
    $invalidData = [
        'guard_id' => $guard->id,
        'zone_id' => 999999,
        'entry_time' => '2025-06-01 07:00:00',
        'exit_time' => '2025-06-01 19:00:00'
    ];

    $response = $this->postJson('/test-guard-report', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['zone_id']);
});

it('requires guard_id field', function () {
    $zone = Zone::factory()->create();
    
    $invalidData = [
        'zone_id' => $zone->id,
        'entry_time' => '2025-06-01 07:00:00',
        'exit_time' => '2025-06-01 19:00:00'
    ];

    $response = $this->postJson('/test-guard-report', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['guard_id']);
});

it('requires zone_id field', function () {
    $guard = Guard::factory()->create();
    
    $invalidData = [
        'guard_id' => $guard->id,
        'entry_time' => '2025-06-01 07:00:00',
        'exit_time' => '2025-06-01 19:00:00'
    ];

    $response = $this->postJson('/test-guard-report', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['zone_id']);
});

it('requires entry_time field', function () {
    $guard = Guard::factory()->create();
    $zone = Zone::factory()->create();
    
    $invalidData = [
        'guard_id' => $guard->id,
        'zone_id' => $zone->id,
        'exit_time' => '2025-06-01 19:00:00'
    ];

    $response = $this->postJson('/test-guard-report', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['entry_time']);
});

it('requires exit_time field', function () {
    $guard = Guard::factory()->create();
    $zone = Zone::factory()->create();
    
    $invalidData = [
        'guard_id' => $guard->id,
        'zone_id' => $zone->id,
        'entry_time' => '2025-06-01 07:00:00'
    ];

    $response = $this->postJson('/test-guard-report', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['exit_time']);
});

it('validates entry_time is valid date', function () {
    $guard = Guard::factory()->create();
    $zone = Zone::factory()->create();
    
    $invalidData = [
        'guard_id' => $guard->id,
        'zone_id' => $zone->id,
        'entry_time' => 'invalid-date',
        'exit_time' => '2025-06-01 19:00:00'
    ];

    $response = $this->postJson('/test-guard-report', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['entry_time']);
});

it('validates exit_time is valid date', function () {
    $guard = Guard::factory()->create();
    $zone = Zone::factory()->create();
    
    $invalidData = [
        'guard_id' => $guard->id,
        'zone_id' => $zone->id,
        'entry_time' => '2025-06-01 07:00:00',
        'exit_time' => 'invalid-date'
    ];

    $response = $this->postJson('/test-guard-report', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['exit_time']);
});

it('allows null incident', function () {
    $guard = Guard::factory()->create();
    $zone = Zone::factory()->create();
    
    $validData = [
        'guard_id' => $guard->id,
        'zone_id' => $zone->id,
        'entry_time' => '2025-06-01 07:00:00',
        'exit_time' => '2025-06-01 19:00:00',
        'incident' => null
    ];

    $response = $this->postJson('/test-guard-report', $validData);

    $response->assertStatus(200);
});

it('validates incident is string when provided', function () {
    $guard = Guard::factory()->create();
    $zone = Zone::factory()->create();
    
    $invalidData = [
        'guard_id' => $guard->id,
        'zone_id' => $zone->id,
        'entry_time' => '2025-06-01 07:00:00',
        'exit_time' => '2025-06-01 19:00:00',
        'incident' => ['not-a-string']
    ];

    $response = $this->postJson('/test-guard-report', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['incident']);
});