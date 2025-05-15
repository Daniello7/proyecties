<?php

namespace App\Livewire\PersonEntries;

use App\Models\Person;
use App\Models\PersonEntry;
use App\Traits\HasTableEloquent;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class ShowTable extends Component
{
    use HasTableEloquent;

    public ?string $activeModal = null;
    public ?int $id = null;
    public ?Person $person = null;
    public ?PersonEntry $entry = null;

    // Form properties
    public $reason;
    public $person_id;
    public $internal_person_id;
    public $arrival_time;
    public $entry_time;
    public $exit_time;
    public $comment;

    public function mount(): void
    {
        $this->columns = ['Contact', 'Reason', 'Porter', 'Arrival', 'Entry', 'Exit', 'Comment', 'Actions'];
        $this->select = [
            'person_entries.id',
            'person_entries.internal_person_id',
            'person_entries.user_id',
            'reason',
            'arrival_time',
            'exit_time',
            'person_entries.comment',
            'entry_time'
        ];
        $this->relations = [
            'user:id,name',
            'internalPerson:id,person_id',
            'internalPerson.person:id,name,last_name',
        ];
        $this->sortColumn = 'exit_time';
        $this->sortDirection = 'desc';
        $this->columnMap = [
            'Reason' => 'person_entries.reason',
            'Porter' => 'person_entries.user_id',
            'Arrival' => 'arrival_time',
            'Entry' => 'entry_time',
            'Exit' => 'exit_time',
            'Contact' => 'internalPerson_personRelation.name',
            'Comment' => null,
            'Actions' => null,
        ];
    }

    private function getEntries(): LengthAwarePaginator
    {
        $query = PersonEntry::query()
            ->with($this->relations)
            ->select($this->select)
            ->joinInternalPerson()
            ->where('person_entries.person_id', $this->person_id)
            ->whereNotNull('exit_time');

        $this->applySearchFilter($query);

        return $query
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(20);
    }

    public function openModal($modal, int $id): void
    {
        $this->id = $id;
        $this->activeModal = $modal;

        if ($modal === 'editEntry') {
            $this->entry = PersonEntry::with(['person', 'internalPerson.person'])->findOrFail($id);
            $this->loadPersonEntryData();
        }
    }

    public function closeModal(): void
    {
        $this->resetExceptConfig(['person_id']);
    }

    private function loadPersonEntryData(): void
    {
        $this->reason = $this->entry->reason;
        $this->person_id = $this->entry->person_id;
        $this->internal_person_id = $this->entry->internal_person_id;
        $this->arrival_time = substr($this->entry->arrival_time, 0, -3);
        $this->entry_time = substr($this->entry->entry_time, 0, -3);
        $this->exit_time = $this->entry->exit_time ? substr($this->entry->exit_time, 0, -3) : null;
        $this->comment = $this->entry->comment;
    }

    public function destroyPersonEntry(int $id): void
    {
        PersonEntry::destroy($id);
        session()->flash('success', __('messages.person-entry_deleted'));

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.person-entries.show-table', ['rows' => $this->getEntries()]);
    }
}
