<?php

use App\Http\Requests\Api\AuthRegisterRequest;
use Illuminate\Support\Facades\Route;
use App\Models\User;

beforeEach(function () {
    Route::post('/test-auth-register', function (AuthRegisterRequest $request) {
        return response()->json(['success' => true]);
    });
});

it('authorizes all users to make the request', function () {
    $request = new AuthRegisterRequest();
    
    expect($request->authorize())->toBeTrue();
});

it('validates request with valid data', function () {
    $validData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];

    $response = $this->postJson('/test-auth-register', $validData);

    $response->assertStatus(200);
});

it('requires name field', function () {
    $invalidData = [
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];

    $response = $this->postJson('/test-auth-register', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

it('validates name is string', function () {
    $invalidData = [
        'name' => ['not-a-string'],
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];

    $response = $this->postJson('/test-auth-register', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

it('validates name max length', function () {
    $invalidData = [
        'name' => str_repeat('a', 256),
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];

    $response = $this->postJson('/test-auth-register', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

it('requires email field', function () {
    $invalidData = [
        'name' => 'Test User',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];

    $response = $this->postJson('/test-auth-register', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('validates email format', function () {
    $invalidData = [
        'name' => 'Test User',
        'email' => 'invalid-email',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];

    $response = $this->postJson('/test-auth-register', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('validates email max length', function () {
    $invalidData = [
        'name' => 'Test User',
        'email' => str_repeat('a', 246) . '@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];

    $response = $this->postJson('/test-auth-register', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('validates email uniqueness', function () {
    $existingUser = User::factory()->create();

    $invalidData = [
        'name' => 'Test User',
        'email' => $existingUser->email,
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];

    $response = $this->postJson('/test-auth-register', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('requires password field', function () {
    $invalidData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password_confirmation' => 'password123'
    ];

    $response = $this->postJson('/test-auth-register', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

it('validates password minimum length', function () {
    $invalidData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => '12',
        'password_confirmation' => '12'
    ];

    $response = $this->postJson('/test-auth-register', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

it('validates password confirmation matches', function () {
    $invalidData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'different-password'
    ];

    $response = $this->postJson('/test-auth-register', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

it('requires password confirmation', function () {
    $invalidData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123'
    ];

    $response = $this->postJson('/test-auth-register', $invalidData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});