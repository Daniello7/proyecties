<?php

namespace App\Listeners;

use App\Events\TokenGeneratedEvent;
use App\Mail\TokenGeneratedMail;
use Illuminate\Support\Facades\Mail;

class SendTokenToEmailListener
{
    public function handle(TokenGeneratedEvent $event): void
    {
        Mail::to($event->user->email)->queue(new TokenGeneratedMail($event->user, $event->token));
    }
}
