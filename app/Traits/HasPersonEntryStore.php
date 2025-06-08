<?php

namespace App\Traits;

use App\Events\NotifyContactVisitorEvent;
use App\Http\Requests\PersonEntry\StorePersonEntryRequest;
use App\Models\InternalPerson;
use App\Models\PersonEntry;

trait HasPersonEntryStore
{
    public ?PersonEntry $entry = null;

    public $reason;
    public $person_id;
    public $internal_person_id;
    public $arrival_time;
    public $entry_time;
    public $exit_time;
    public $comment;
    public $notify = false;
    public $enter = false;

    protected $formRequest;

    public function storePersonEntry(): void
    {
        $this->authorize('create', PersonEntry::class);

        $this->formRequest = new StorePersonEntryRequest();

        $validated = $this->validate($this->formRequest->rules());
        $validated['user_id'] = auth()->user()->id;
        $validated['arrival_time'] = now();

        if ($this->enter) $validated['entry_time'] = now();

        $this->entry = PersonEntry::create($validated);

        session()->flash('success', __('messages.person-entry_created'));

        if ($this->notify) event(new NotifyContactVisitorEvent($this->entry));

        if ($this->reason == 'Charge' || $this->reason == 'Discharge') {
            $pdfUrl = route('driver-rules', ['person' => $this->person]);
        } elseif ($this->reason == 'Cleaning') {
            $pdfUrl = route('cleaning-rules', ['person' => $this->person]);
        } else {
            $pdfUrl = route('visitor-rules', ['person' => $this->person]);
        }

        $this->dispatch('openRulesPdf', url: $pdfUrl);

        $this->closeModal();
    }
}
