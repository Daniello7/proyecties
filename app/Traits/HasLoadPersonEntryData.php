<?php

namespace App\Traits;

trait HasLoadPersonEntryData
{
    protected function loadPersonEntryData(): void
    {
        $this->reason = $this->entry->reason;
        $this->person_id = $this->entry->person_id;
        $this->internal_person_id = $this->entry->internal_person_id;
        $this->arrival_time = substr($this->entry->arrival_time, 0, -3);
        $this->entry_time = substr($this->entry->entry_time, 0, -3);
        $this->exit_time = $this->entry->exit_time ? substr($this->entry->exit_time, 0, -3) : null;
        $this->comment = $this->entry->comment;
    }
}
