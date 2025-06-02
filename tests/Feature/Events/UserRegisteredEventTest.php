<?php

namespace Tests\Feature\Events;

use App\Events\UserRegisteredEvent;
use App\Models\User;

beforeEach(function() {
    $this->user = User::factory()->create([
        'email' => 'nuevo@example.com',
        'name' => 'Usuario Nuevo'
    ]);
});

it('should construct event with user', function() {
    // Act
    $event = new UserRegisteredEvent($this->user);
    
    // Assert
    expect($event->user)->toBe($this->user);
});

it('should be dispatchable', function() {
    // Arrange
    $wasDispatched = false;
    
    // Register event listener
    \Event::listen(UserRegisteredEvent::class, function($event) use (&$wasDispatched) {
        $wasDispatched = true;
        expect($event->user)->toBe($this->user);
    });
    
    // Act
    UserRegisteredEvent::dispatch($this->user);
    
    // Assert
    expect($wasDispatched)->toBeTrue();
});

it('should throw type error when invalid user is provided', function() {
    expect(fn() => new UserRegisteredEvent('no es un usuario'))
        ->toThrow(\TypeError::class);
});