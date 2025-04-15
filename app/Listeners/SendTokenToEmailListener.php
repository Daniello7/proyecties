<?php

namespace App\Listeners;

use App\Events\TokenGeneratedEvent;
use App\Mail\TokenGeneratedMail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendTokenToEmailListener
{
    public function handle(TokenGeneratedEvent $event): void
    {
        Mail::to($event->user->email)->queue(new TokenGeneratedMail($event->user, $event->token));

        if (App::environment('local')) {
            Storage::append('tokens.md',
                "## {$event->user->name}" .
                "\r * Email: {$event->user->email}" .
                "\r * Token: $event->token" .
                "\r * Abilities: [" . join(', ', $event->user->tokens->first()->abilities) . "] \n");
        }
    }
}
