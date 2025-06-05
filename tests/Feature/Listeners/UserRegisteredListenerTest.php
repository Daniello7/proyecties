<?php

use App\Events\UserRegisteredEvent;
use App\Mail\WelcomeNewUserMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

it('sends welcome email to newly registered user', function () {
    // Arrange
    Mail::fake();
    $user = User::factory()->create([
        'email' => 'test@example.com'
    ]);

    // Act
    event(new UserRegisteredEvent($user));

    // Assert
    Mail::assertQueued(WelcomeNewUserMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

it('queues exactly one welcome email per registration', function () {
    // Arrange
    Mail::fake();
    $user = User::factory()->create();

    // Act
    event(new UserRegisteredEvent($user));

    // Assert
    Mail::assertQueued(WelcomeNewUserMail::class, 1);
});