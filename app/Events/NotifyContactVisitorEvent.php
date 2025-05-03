<?php

namespace App\Events;

use App\Models\PersonEntry;
use Illuminate\Foundation\Events\Dispatchable;

class NotifyContactVisitorEvent
{
    use Dispatchable;

    public string $email;
    public string $message;

    public function __construct(PersonEntry $personEntry)
    {
        $this->email = $personEntry->internalPerson->email;
        $this->message = __('Hello!') . " {$personEntry->internalPerson->person->name}.\n\n\t" .
            __('New visit has arrived') . ":\n\t" .
            "DNI/NIE: {$personEntry->person->document_number}\n\t" .
            __('Name') . ": {$personEntry->person->name}\n\t" .
            __('Surname') . ": {$personEntry->person->last_name}\n\t" .
            __('Arrival time') . ": {$personEntry->arrival_time}\n" .
            (trim($personEntry->comment) != '' ? __('Comment') . ": {$personEntry->comment}\n" : '');
    }
}
