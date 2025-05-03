<?php

namespace App\Listeners;

use App\Events\NotifyContactPackageEvent;
use App\Mail\NotifyContactPackageMail;
use Illuminate\Support\Facades\Mail;

class NotifyContactPackageListener
{
    public function handle(NotifyContactPackageEvent $event): void
    {
        Mail::to($event->email)->queue(new NotifyContactPackageMail($event->message));
    }
}
