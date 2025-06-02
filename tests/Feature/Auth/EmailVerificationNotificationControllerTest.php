<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailVerificationNotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_notification_can_be_sent(): void
    {
        // Arrange
        Notification::fake();
        
        $user = User::factory()->unverified()->create();
        
        // Act
        $response = $this->actingAs($user)
            ->post(route('verification.send'));

        // Assert
        $response->assertSessionHas('status', 'verification-link-sent');
        $response->assertRedirect();
        
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_verified_users_are_redirected_to_dashboard(): void
    {
        // Arrange
        Notification::fake();
        
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        
        // Act
        $response = $this->actingAs($user)
            ->post(route('verification.send'));

        // Assert
        $response->assertRedirect(route('dashboard'));
        
        Notification::assertNothingSent();
    }

    public function test_guests_cannot_send_verification_notification(): void
    {
        // Act
        $response = $this->post(route('verification.send'));

        // Assert
        $response->assertRedirect(route('login'));
    }
}