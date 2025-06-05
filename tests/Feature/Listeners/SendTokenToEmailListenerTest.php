<?php

use App\Events\TokenGeneratedEvent;
use App\Mail\TokenGeneratedMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\NewAccessToken;

it('sends email with token information', function () {
    // Arrange
    Mail::fake();
    $user = User::factory()->create();
    $token = 'test-token-123';
    $abilities = ['read', 'write'];
    
    $accessToken = new NewAccessToken($user->createToken('Test Token', $abilities)->accessToken, $token);
    $user->tokens()->save($accessToken->accessToken);

    // Act
    event(new TokenGeneratedEvent($user, $token));

    // Assert
    Mail::assertQueued(TokenGeneratedMail::class, function ($mail) use ($user, $token) {
        return $mail->hasTo($user->email);
    });
});

it('stores token information in local environment', function () {
    // Arrange
    Mail::fake();
    Storage::fake('local');
    app()['env'] = 'local';
    
    $user = User::factory()->create();
    $token = 'test-token-123';
    $abilities = ['read', 'write'];
    
    $accessToken = new NewAccessToken($user->createToken('Test Token', $abilities)->accessToken, $token);
    $user->tokens()->save($accessToken->accessToken);

    // Act
    event(new TokenGeneratedEvent($user, $token));

    // Assert
    Storage::assertExists('tokens.md');
    $content = Storage::get('tokens.md');
    expect($content)->toContain($user->name)
        ->toContain($user->email)
        ->toContain($token)
        ->toContain('read, write');
});

it('does not store token information in non-local environment', function () {
    // Arrange
    Mail::fake();
    Storage::fake('local');
    app()['env'] = 'production';
    
    $user = User::factory()->create();
    $token = 'test-token-123';
    $abilities = ['read', 'write'];
    
    $accessToken = new NewAccessToken($user->createToken('Test Token', $abilities)->accessToken, $token);
    $user->tokens()->save($accessToken->accessToken);

    // Act
    event(new TokenGeneratedEvent($user, $token));

    // Assert
    Storage::assertMissing('tokens.md');
});