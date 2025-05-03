<?php

namespace App\Listeners;

use App\Events\NotifyContactVisitorEvent;
use App\Mail\NotifyContactVisitorMail;
use Illuminate\Support\Facades\Mail;

class NotifyContactVisitorMailListener
{

    public function handle(NotifyContactVisitorEvent $event): void
    {
        Mail::to($event->email)->queue(new NotifyContactVisitorMail($event->message));
    }
}
