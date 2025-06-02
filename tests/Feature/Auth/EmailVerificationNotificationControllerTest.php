<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

it('can send email verification notification', function () {
    Notification::fake();
    
    $user = User::factory()->unverified()->create();
    
    $response = $this->actingAs($user)
        ->post(route('verification.send'));

    $response->assertSessionHas('status', 'verification-link-sent');
    $response->assertRedirect();
    
    Notification::assertSentTo($user, VerifyEmail::class);
});

it('redirects verified users to dashboard', function () {
    Notification::fake();
    
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    
    $response = $this->actingAs($user)
        ->post(route('verification.send'));

    $response->assertRedirect(route('dashboard'));
    
    Notification::assertNothingSent();
});

it('prevents guests from sending verification notification', function () {
    $response = $this->post(route('verification.send'));

    $response->assertRedirect(route('login'));
});