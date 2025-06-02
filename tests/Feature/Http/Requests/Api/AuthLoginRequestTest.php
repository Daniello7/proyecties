<?php

use App\Http\Requests\Api\AuthLoginRequest;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::post('/test-auth-login', function (AuthLoginRequest $request) {
        return response()->json(['success' => true]);
    });
});

it('authorizes all users to make the request', function () {
    $request = new AuthLoginRequest();
    
    expect($request->authorize())->toBeTrue();
});

it('validates request with valid data', function () {
    $validData = [
        'email' => 'test@example.com',
        'password' => 'password123'
    ];

    $response = $this->postJson('/test-auth-login', $validData);

    $response->assertStatus(200);
});

it('requires email field', function () {
    $invalidData = [
        'password' => 'password123'
    ];

    $response = $this->postJson('/test-auth-login', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('requires password field', function () {
    $invalidData = [
        'email' => 'test@example.com'
    ];

    $response = $this->postJson('/test-auth-login', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

it('validates email format', function () {
    $invalidData = [
        'email' => 'invalid-email',
        'password' => 'password123'
    ];

    $response = $this->postJson('/test-auth-login', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('validates email is string', function () {
    $invalidData = [
        'email' => ['not-a-string'],
        'password' => 'password123'
    ];

    $response = $this->postJson('/test-auth-login', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('validates password is string', function () {
    $invalidData = [
        'email' => 'test@example.com',
        'password' => ['not-a-string']
    ];

    $response = $this->postJson('/test-auth-login', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});