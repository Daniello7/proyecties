<?php

namespace App\Livewire\PersonEntries;

use App\Http\Requests\Person\StorePersonRequest;
use App\Models\Person;
use Livewire\Component;

class Index extends Component
{
    public bool $openedLastestEntriesTable = false;
    public bool $openedPersonListTable = false;
    public bool $openedPersonCreate = false;

    public function mount(): void
    {
        $this->openedLastestEntriesTable = true;
    }

    public function openLastestEntriesTable(): void
    {
        $this->closeAll();
        $this->openedLastestEntriesTable = true;
    }

    public function openPersonListTable(): void
    {
        $this->closeAll();
        $this->openedPersonListTable = true;
    }

    public function openPersonCreate(): void
    {
        $this->closeAll();
        $this->openedPersonCreate = true;
    }

    public function closeAll(): void
    {
        $this->openedLastestEntriesTable = false;
        $this->openedPersonListTable = false;
        $this->openedPersonCreate = false;
    }

    public function render()
    {
        return view('livewire.person-entries.index');
    }
}
