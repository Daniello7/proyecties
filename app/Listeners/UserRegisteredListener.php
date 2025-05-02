<?php

namespace App\Listeners;

use App\Events\UserRegisteredEvent;
use App\Mail\WelcomeNewUserMail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class UserRegisteredListener
{
    use SerializesModels;

    public function handle(UserRegisteredEvent $event): void
    {
        Mail::to($event->user->email)->queue(new WelcomeNewUserMail($event->user));
    }
}
