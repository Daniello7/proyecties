<?php

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

test('login request validates required fields', function () {
    $request = new LoginRequest();
    
    expect($request->rules())->toHaveKeys(['email', 'password'])
        ->and($request->rules()['email'])->toContain('required', 'string', 'email')
        ->and($request->rules()['password'])->toContain('required', 'string');
});

test('login request is always authorized', function () {
    $request = new LoginRequest();
    
    expect($request->authorize())->toBeTrue();
});

test('authentication is rate limited after too many attempts', function () {
    Event::fake();

    $user = User::factory()->create();
    $request = new LoginRequest();
    $request->merge([
        'email' => $user->email,
        'password' => 'wrong-password'
    ]);

    for ($i = 0; $i < 5; $i++) {
        try {
            $request->authenticate();
        } catch (ValidationException $e) {
            continue;
        }
    }

    try {
        $request->authenticate();
    } catch (ValidationException $e) {
        expect($e->errors()['email'][0])->toContain('Demasiados intentos de acceso');
        Event::assertDispatched(Lockout::class);
        return;
    }

    $this->fail('Se esperaba una excepción ValidationException');
});

test('throttle key is generated correctly', function () {
    $request = new LoginRequest();
    $request->merge(['email' => 'test@example.com']);
    
    $expectedKey = 'test@example.com|' . $request->ip();
    
    expect($request->throttleKey())->toBe($expectedKey);
});

test('rate limiter is cleared after successful login', function () {
    $user = User::factory()->create();
    $request = new LoginRequest();
    $request->merge([
        'email' => $user->email,
        'password' => 'password'
    ]);

    $request->authenticate();
    
    expect(RateLimiter::tooManyAttempts($request->throttleKey(), 5))->toBeFalse();
});

test('rate limiter hit is incremented after failed login', function () {
    $request = new LoginRequest();
    $request->merge([
        'email' => 'test@example.com',
        'password' => 'wrong-password'
    ]);

    try {
        $request->authenticate();
    } catch (ValidationException $e) {
        expect(RateLimiter::attempts($request->throttleKey()))->toBe(1);
        return;
    }

    $this->fail('Se esperaba una excepción ValidationException');
});