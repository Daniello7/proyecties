<?php

use App\Http\Requests\Api\ZoneRequest;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::post('/test-zone', function (ZoneRequest $request) {
        return response()->json(['success' => true]);
    });
});

it('authorizes all users to make the request', function () {
    $request = new ZoneRequest();
    
    expect($request->authorize())->toBeTrue();
});

it('validates request with valid data', function () {
    $validData = [
        'name' => 'Test Zone',
        'location' => 'C/: Test Street, 123'
    ];

    $response = $this->postJson('/test-zone', $validData);

    $response->assertStatus(200);
});

it('requires name field', function () {
    $invalidData = [
        'location' => 'C/: Test Street, 123'
    ];

    $response = $this->postJson('/test-zone', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

it('requires location field', function () {
    $invalidData = [
        'name' => 'Test Zone'
    ];

    $response = $this->postJson('/test-zone', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['location']);
});

it('validates name is string', function () {
    $invalidData = [
        'name' => ['not-a-string'],
        'location' => 'C/: Test Street, 123'
    ];

    $response = $this->postJson('/test-zone', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

it('validates location is string', function () {
    $invalidData = [
        'name' => 'Test Zone',
        'location' => ['not-a-string']
    ];

    $response = $this->postJson('/test-zone', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['location']);
});

it('validates name max length', function () {
    $invalidData = [
        'name' => str_repeat('a', 256),
        'location' => 'C/: Test Street, 123'
    ];

    $response = $this->postJson('/test-zone', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

it('validates location max length', function () {
    $invalidData = [
        'name' => 'Test Zone',
        'location' => str_repeat('a', 256)
    ];

    $response = $this->postJson('/test-zone', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['location']);
});