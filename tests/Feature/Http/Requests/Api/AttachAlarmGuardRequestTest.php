<?php

use App\Http\Requests\Api\AttachAlarmGuardRequest;
use Illuminate\Support\Facades\Route;
use App\Models\Api\Alarm;
use App\Models\Api\Guard;

beforeEach(function () {
    Route::post('/test-attach-alarm-guard', function (AttachAlarmGuardRequest $request) {
        return response()->json(['success' => true]);
    });
});

it('authorizes all users to make the request', function () {
    $request = new AttachAlarmGuardRequest();
    
    expect($request->authorize())->toBeTrue();
});

it('validates request with valid data', function () {
    $alarm = Alarm::factory()->create();
    $guard = Guard::factory()->create();
    
    $validData = [
        'alarm_id' => $alarm->id,
        'guard_id' => $guard->id,
        'is_false_alarm' => true,
        'notes' => 'Test notes'
    ];

    $response = $this->postJson('/test-attach-alarm-guard', $validData);

    $response->assertStatus(200);
});

it('validates alarm_id exists', function () {
    $guard = Guard::factory()->create();
    
    $invalidData = [
        'alarm_id' => 999999,
        'guard_id' => $guard->id,
        'is_false_alarm' => true
    ];

    $response = $this->postJson('/test-attach-alarm-guard', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['alarm_id']);
});

it('validates guard_id exists', function () {
    $alarm = Alarm::factory()->create();
    
    $invalidData = [
        'alarm_id' => $alarm->id,
        'guard_id' => 999999,
        'is_false_alarm' => true
    ];

    $response = $this->postJson('/test-attach-alarm-guard', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['guard_id']);
});

it('requires alarm_id to be integer', function () {
    $guard = Guard::factory()->create();
    
    $invalidData = [
        'alarm_id' => 'not-an-integer',
        'guard_id' => $guard->id,
        'is_false_alarm' => true
    ];

    $response = $this->postJson('/test-attach-alarm-guard', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['alarm_id']);
});

it('requires guard_id to be integer', function () {
    $alarm = Alarm::factory()->create();
    
    $invalidData = [
        'alarm_id' => $alarm->id,
        'guard_id' => 'not-an-integer',
        'is_false_alarm' => true
    ];

    $response = $this->postJson('/test-attach-alarm-guard', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['guard_id']);
});

it('requires is_false_alarm field', function () {
    $alarm = Alarm::factory()->create();
    $guard = Guard::factory()->create();
    
    $invalidData = [
        'alarm_id' => $alarm->id,
        'guard_id' => $guard->id
    ];

    $response = $this->postJson('/test-attach-alarm-guard', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['is_false_alarm']);
});

it('validates is_false_alarm is boolean', function () {
    $alarm = Alarm::factory()->create();
    $guard = Guard::factory()->create();
    
    $invalidData = [
        'alarm_id' => $alarm->id,
        'guard_id' => $guard->id,
        'is_false_alarm' => 'not-a-boolean'
    ];

    $response = $this->postJson('/test-attach-alarm-guard', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['is_false_alarm']);
});

it('allows null notes', function () {
    $alarm = Alarm::factory()->create();
    $guard = Guard::factory()->create();
    
    $validData = [
        'alarm_id' => $alarm->id,
        'guard_id' => $guard->id,
        'is_false_alarm' => true,
        'notes' => null
    ];

    $response = $this->postJson('/test-attach-alarm-guard', $validData);

    $response->assertStatus(200);
});

it('validates notes max length', function () {
    $alarm = Alarm::factory()->create();
    $guard = Guard::factory()->create();
    
    $invalidData = [
        'alarm_id' => $alarm->id,
        'guard_id' => $guard->id,
        'is_false_alarm' => true,
        'notes' => str_repeat('a', 256)
    ];

    $response = $this->postJson('/test-attach-alarm-guard', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['notes']);
});

it('validates notes is string when provided', function () {
    $alarm = Alarm::factory()->create();
    $guard = Guard::factory()->create();
    
    $invalidData = [
        'alarm_id' => $alarm->id,
        'guard_id' => $guard->id,
        'is_false_alarm' => true,
        'notes' => ['not-a-string']
    ];

    $response = $this->postJson('/test-attach-alarm-guard', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['notes']);
});