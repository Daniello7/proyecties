<?php

namespace App\Events;

use App\Models\Package;
use Illuminate\Foundation\Events\Dispatchable;

class NotifyContactPackageEvent
{
    use Dispatchable;

    public string $email;
    public string $message;

    public function __construct(Package $package)
    {
        $this->email = $package->internalPerson->email;
        $this->message = __('Hello!') . " {$package->internalPerson->person->name}.\n\n\t" .
            __('New package has arrived') . ":\n\t" .
            __('Sender') . ": {$package->external_entity}\n\t" .
            __('Agency') . ": {$package->agency}\n\t" .
            __('Package Count') . ": {$package->package_count}\n\t" .
            __('Arrival time') . ": {$package->entry_time}\n\t" .
            __('Receiver') . ": {$package->receiver->name}\n\n" .
            (trim($package->comment) != '' ? __('Comment') . ": {$package->comment}\n" : '');
    }
}
