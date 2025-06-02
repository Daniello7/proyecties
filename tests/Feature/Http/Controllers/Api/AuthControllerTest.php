<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Events\TokenGeneratedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    Event::fake([TokenGeneratedEvent::class]);
});

it('can register a new user', function () {
    // Arrange
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];

    // Act
    $response = $this->postJson('/api/register', $userData);

    // Assert
    $response->assertCreated()
        ->assertJsonStructure([
            'message',
            'user' => [
                'id',
                'name',
                'email'
            ]
        ]);

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com'
    ]);
});

it('prevents registration with existing email', function () {
    // Arrange
    $existingUser = User::factory()->create();
    
    $userData = [
        'name' => 'Test User',
        'email' => $existingUser->email,
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];

    // Act
    $response = $this->postJson('/api/register', $userData);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('requires password confirmation for registration', function () {
    // Arrange
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123'
    ];

    // Act
    $response = $this->postJson('/api/register', $userData);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);
});

it('can login with correct credentials', function () {
    // Arrange
    $password = 'correct-password';
    $user = User::factory()->create([
        'password' => Hash::make($password)
    ]);

    $credentials = [
        'email' => $user->email,
        'password' => $password
    ];

    // Act
    $response = $this->postJson('/api/login', $credentials);

    // Assert
    $response->assertOk()
        ->assertJsonStructure([
            'message',
            'user' => [
                'id',
                'name',
                'email'
            ],
            'token'
        ]);

    Event::assertDispatched(TokenGeneratedEvent::class);
});

it('cannot login with incorrect password', function () {
    // Arrange
    $user = User::factory()->create([
        'password' => Hash::make('correct-password')
    ]);

    $credentials = [
        'email' => $user->email,
        'password' => 'wrong-password'
    ];

    // Act
    $response = $this->postJson('/api/login', $credentials);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);

    Event::assertNotDispatched(TokenGeneratedEvent::class);
});

it('deletes existing tokens when logging in', function () {
    // Arrange
    $password = 'correct-password';
    $user = User::factory()->create([
        'password' => Hash::make($password)
    ]);
    
    // Crear token existente
    $user->createToken('old-token');

    $credentials = [
        'email' => $user->email,
        'password' => $password
    ];

    // Act
    $response = $this->postJson('/api/login', $credentials);

    // Assert
    $response->assertOk();
    $this->assertEquals(1, $user->tokens()->count());
});

it('can logout and delete tokens', function () {
    // Arrange
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $user->createToken('test-token');

    // Act
    $response = $this->postJson('/api/logout');

    // Assert
    $response->assertOk()
        ->assertJsonStructure(['message']);
    
    $this->assertEquals(0, $user->tokens()->count());
});

it('validates required fields for registration', function () {
    // Act
    $response = $this->postJson('/api/register', []);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});

it('validates required fields for login', function () {
    // Act
    $response = $this->postJson('/api/login', []);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email', 'password']);
});

it('validates email format for registration', function () {
    // Arrange
    $userData = [
        'name' => 'Test User',
        'email' => 'invalid-email',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];

    // Act
    $response = $this->postJson('/api/register', $userData);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('requires authentication for logout', function () {
    // Act
    $response = $this->postJson('/api/logout');

    // Assert
    $response->assertUnauthorized();
});