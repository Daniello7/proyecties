<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GeneratedActiveEntriesPdfEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $user_id;

    public function __construct(int $user_id)
    {
        $this->user_id = $user_id;
    }
}
