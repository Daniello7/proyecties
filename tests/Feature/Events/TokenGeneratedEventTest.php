<?php

namespace Tests\Feature\Events;

use App\Events\TokenGeneratedEvent;
use App\Models\User;

beforeEach(function() {
    $this->user = User::factory()->create([
        'email' => 'test@example.com',
        'name' => 'Test User'
    ]);
    
    $this->token = 'test-token-123';
});

it('should construct event with user and token', function() {
    // Act
    $event = new TokenGeneratedEvent($this->user, $this->token);
    
    // Assert
    expect($event)
        ->user->toBe($this->user)
        ->token->toBe('test-token-123');
});

it('should be dispatchable', function() {
    // Arrange
    $wasDispatched = false;
    
    // Register event listener
    \Event::listen(TokenGeneratedEvent::class, function($event) use (&$wasDispatched) {
        $wasDispatched = true;
        expect($event)
            ->user->toBe($this->user)
            ->token->toBe($this->token);
    });
    
    // Act
    TokenGeneratedEvent::dispatch($this->user, $this->token);
    
    // Assert
    expect($wasDispatched)->toBeTrue();
});

it('should maintain token integrity', function() {
    // Arrange
    $longToken = str_repeat('a', 100);
    
    // Act
    $event = new TokenGeneratedEvent($this->user, $longToken);
    
    // Assert
    expect($event->token)->toBe($longToken);
});

it('should throw type error when invalid user is provided', function() {
    expect(fn() => new TokenGeneratedEvent('not a user', $this->token))
        ->toThrow(\TypeError::class);
});

it('should throw type error when invalid token is provided', function() {
    expect(fn() => new TokenGeneratedEvent($this->user, ['not a string']))
        ->toThrow(\TypeError::class);
});