<?php

use App\Http\Requests\Api\AlarmRequest;
use Illuminate\Support\Facades\Route;
use App\Models\Api\Zone;

beforeEach(function () {
    Route::post('/test-alarm', function (AlarmRequest $request) {
        return response()->json(['success' => true]);
    });
});

it('authorizes all users to make the request', function () {
    $request = new AlarmRequest();
    
    expect($request->authorize())->toBeTrue();
});

it('validates request with valid data', function () {
    $zone = Zone::factory()->create();
    
    $validData = [
        'zone_id' => $zone->id,
        'type' => 'intrusion',
        'is_active' => true,
        'description' => 'Test alarm description'
    ];

    $response = $this->postJson('/test-alarm', $validData);

    $response->assertStatus(200);
});

it('validates zone_id exists', function () {
    $validData = [
        'zone_id' => 999999, // Non-existent ID
        'type' => 'intrusion',
        'is_active' => true
    ];

    $response = $this->postJson('/test-alarm', $validData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['zone_id']);
});

it('requires zone_id to be integer', function () {
    $validData = [
        'zone_id' => 'not-an-integer',
        'type' => 'intrusion',
        'is_active' => true
    ];

    $response = $this->postJson('/test-alarm', $validData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['zone_id']);
});

it('requires type field', function () {
    $zone = Zone::factory()->create();
    
    $invalidData = [
        'zone_id' => $zone->id,
        'is_active' => true
    ];

    $response = $this->postJson('/test-alarm', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['type']);
});

it('validates type max length', function () {
    $zone = Zone::factory()->create();
    
    $invalidData = [
        'zone_id' => $zone->id,
        'type' => str_repeat('a', 101), // String longer than 100 characters
        'is_active' => true
    ];

    $response = $this->postJson('/test-alarm', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['type']);
});

it('requires is_active field', function () {
    $zone = Zone::factory()->create();
    
    $invalidData = [
        'zone_id' => $zone->id,
        'type' => 'intrusion'
    ];

    $response = $this->postJson('/test-alarm', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['is_active']);
});

it('validates is_active is boolean', function () {
    $zone = Zone::factory()->create();
    
    $invalidData = [
        'zone_id' => $zone->id,
        'type' => 'intrusion',
        'is_active' => 'not-a-boolean'
    ];

    $response = $this->postJson('/test-alarm', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['is_active']);
});

it('allows null description', function () {
    $zone = Zone::factory()->create();
    
    $validData = [
        'zone_id' => $zone->id,
        'type' => 'intrusion',
        'is_active' => true,
        'description' => null
    ];

    $response = $this->postJson('/test-alarm', $validData);

    $response->assertStatus(200);
});

it('validates description is string when provided', function () {
    $zone = Zone::factory()->create();
    
    $invalidData = [
        'zone_id' => $zone->id,
        'type' => 'intrusion',
        'is_active' => true,
        'description' => ['not-a-string']
    ];

    $response = $this->postJson('/test-alarm', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['description']);
});