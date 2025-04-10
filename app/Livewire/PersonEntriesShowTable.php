<?php

namespace App\Livewire;

use App\Models\PersonEntry;
use App\Traits\HasTableEloquent;
use Livewire\Component;

class PersonEntriesShowTable extends Component
{
    use HasTableEloquent;

    public int $person_id;

    public function mount()
    {
        $this->applyTableConfiguration();
    }

    private function applyTableConfiguration(): void
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

    private function getEntries()
    {
        $query = PersonEntry::query()
            ->with($this->relations)
            ->select($this->select)
            ->join('internal_people as internalPerson',
                'person_entries.internal_person_id', '=', 'internalPerson.id')
            ->join('people as internalPerson_personRelation',
                'internalPerson.person_id', '=', 'internalPerson_personRelation.id')
            ->where('person_entries.person_id', $this->person_id)
            ->whereNotNull('exit_time');

        $this->applySearchFilter($query);

        return $query
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(20);
    }

    public function destroyPersonEntry(int $id): void
    {
        PersonEntry::destroy($id);
        session()->flash('success', __('messages.person-entry_deleted'));
    }

    public function render()
    {
        return view('livewire.person-entries-show-table', ['rows' => $this->getEntries()]);
    }
}
