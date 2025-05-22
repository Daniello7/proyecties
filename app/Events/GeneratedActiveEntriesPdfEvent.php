<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GeneratedActiveEntriesPdfEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public function __construct()
    {
        session()->flash('success', __('messages.document_created'));
    }
}
