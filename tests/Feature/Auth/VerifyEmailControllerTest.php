<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    $this->controller = new VerifyEmailController();
});

describe('VerifyEmailController', function () {
    beforeEach(function () {
        Event::fake();
    });

    it('redirects already verified users without triggering event', function () {
        // Arrange
        $user = Mockery::mock(User::class);
        $user->shouldReceive('hasVerifiedEmail')->once()->andReturn(true);
        
        $request = Mockery::mock(EmailVerificationRequest::class);
        $request->shouldReceive('user')->times(1)->andReturn($user);
        
        // Act
        $response = $this->controller->__invoke($request);
        
        // Assert
        expect($response)
            ->toBeInstanceOf(Illuminate\Http\RedirectResponse::class)
            ->and($response->getTargetUrl())
            ->toBe(route('dashboard') . '?verified=1');
            
        Event::assertNotDispatched(Verified::class);
    });

    it('verifies email and dispatches event for unverified users', function () {
        // Arrange
        $user = Mockery::mock(User::class);
        $user->shouldReceive('hasVerifiedEmail')->once()->andReturn(false);
        $user->shouldReceive('markEmailAsVerified')->once()->andReturn(true);
        
        $request = Mockery::mock(EmailVerificationRequest::class);
        $request->shouldReceive('user')->times(3)->andReturn($user);
        
        // Act
        $response = $this->controller->__invoke($request);
        
        // Assert
        expect($response)
            ->toBeInstanceOf(Illuminate\Http\RedirectResponse::class)
            ->and($response->getTargetUrl())
            ->toBe(route('dashboard') . '?verified=1');
            
        Event::assertDispatched(Verified::class, function ($event) use ($user) {
            return $event->user === $user;
        });
    });

    it('handles failed email verification without dispatching event', function () {
        // Arrange
        $user = Mockery::mock(User::class);
        $user->shouldReceive('hasVerifiedEmail')->once()->andReturn(false);
        $user->shouldReceive('markEmailAsVerified')->once()->andReturn(false);

        $request = Mockery::mock(EmailVerificationRequest::class);
        $request->shouldReceive('user')->times(2)->andReturn($user);

        // Act
        $response = $this->controller->__invoke($request);

        // Assert
        expect($response)
            ->toBeInstanceOf(Illuminate\Http\RedirectResponse::class)
            ->and($response->getTargetUrl())
            ->toBe(route('dashboard') . '?verified=1');

        Event::assertNotDispatched(Verified::class);
    });
});