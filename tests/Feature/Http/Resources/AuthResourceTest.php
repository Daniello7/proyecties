<?php

namespace Tests\Feature\Http\Resources;

use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Mockery;

it('correctly transforms user data', function () {
    // Arrange
    $user = Mockery::mock(User::class);
    $user->shouldReceive('getAttribute')->with('id')->andReturn(1);
    $user->shouldReceive('getAttribute')->with('name')->andReturn('John Doe');
    $user->shouldReceive('getAttribute')->with('email')->andReturn('john@example.com');

    // Act
    $resource = new AuthResource($user);
    $result = $resource->toArray(Request::createFromGlobals());

    // Assert
    expect($result)
        ->toBeArray()
        ->toHaveKeys(['id', 'name', 'email'])
        ->and($result['id'])->toBe(1)
        ->and($result['name'])->toBe('John Doe')
        ->and($result['email'])->toBe('john@example.com');
});