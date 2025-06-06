<?php

use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Models\User;
use Illuminate\Http\Request;

beforeEach(function () {
    $this->controller = new EmailVerificationPromptController();
});

describe('EmailVerificationPromptController', function () {
    it('redirects verified users to dashboard', function () {
        // Arrange
        $user = Mockery::mock(User::class);
        $user->shouldReceive('hasVerifiedEmail')->once()->andReturn(true);
        
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('user')->once()->andReturn($user);
        
        // Act
        $response = $this->controller->__invoke($request);
        
        // Assert
        expect($response)
            ->toBeInstanceOf(Illuminate\Http\RedirectResponse::class)
            ->and($response->getTargetUrl())
            ->toBe(route('dashboard'));
    });

    it('shows verify email view for unverified users', function () {
        // Arrange
        $user = Mockery::mock(User::class);
        $user->shouldReceive('hasVerifiedEmail')->once()->andReturn(false);
        
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('user')->once()->andReturn($user);
        
        // Act
        $response = $this->controller->__invoke($request);
        
        // Assert
        expect($response)
            ->toBeInstanceOf(Illuminate\View\View::class)
            ->and($response->getName())
            ->toBe('auth.verify-email');
    });
});