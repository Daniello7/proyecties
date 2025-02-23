<?php

namespace App\Listeners;

use App\Mail\WelcomeNewUserMail;
use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class UserRegisteredListener
{
    use SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(UserRegisteredListener $event): void
    {
        Mail::to($event->user->email)->queue(new WelcomeNewUserMail($event->user));
    }
}
