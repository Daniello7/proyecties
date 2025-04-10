<?php

namespace App\Livewire;

use App\Models\Person;
use App\Models\PersonEntry;
use App\Traits\HasTableEloquent;
use Livewire\Component;

class PersonEntriesHomeTable extends Component
{
    use HasTableEloquent;

    public function mount()
    {
        $this->applyTableConfiguration();
    }

    private function applyTableConfiguration(): void
    {
        $this->columns = ['Name', 'Company', 'Contact', 'Comment', 'Actions'];
        $this->select = [
            'person_entries.id',
            'person_entries.person_id',
            'person_entries.internal_person_id',
            'person_entries.comment',
            'entry_time'
        ];
        $this->relations = [
            'person:id,name,last_name,company',
            'internalPerson:id,person_id',
            'internalPerson.person:id,name,last_name',
        ];
        $this->sortColumn = 'arrival_time';
        $this->sortDirection = 'asc';
        $this->columnMap = [
            'Name' => 'person.name',
            'Company' => 'person.company',
            'Contact' => 'internalPerson_personRelation.name',
            'Comment' => null,
            'Actions' => null,
        ];
    }

    private function getEntries()
    {
        $externalPeople = Person::query()
            ->select('id')
            ->doesntHave('internalPerson');

        $query = PersonEntry::query()
            ->with($this->relations)
            ->select($this->select)
            ->whereIn('person_entries.person_id', $externalPeople)
            ->join('people as person', 'person_entries.person_id', '=', 'person.id')
            ->join('internal_people as internalPerson',
                'person_entries.internal_person_id', '=', 'internalPerson.id')
            ->join('people as internalPerson_personRelation',
                'internalPerson.person_id', '=', 'internalPerson_personRelation.id')
            ->whereNull('exit_time');

        $this->applySearchFilter($query);

        return $query
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(50);
    }

    public function updateEntry(int $id): void
    {
        $personEntry = PersonEntry::find($id);

        $personEntry->update(['entry_time' => now()]);

        session()->flash('success', __('messages.person-entry_updated'));
    }

    public function updateExit(int $id): void
    {
        $personEntry = PersonEntry::find($id);

        $personEntry->update(['exit_time' => now()]);

        session()->flash('success', __('messages.person-entry_exited'));
    }

    public function destroyPersonEntry(int $id): void
    {
        PersonEntry::destroy($id);
        session()->flash('success', __('messages.person-entry_deleted'));
    }

    public function render()
    {
        return view('livewire.person-entries-home',
            ['rows' => $this->getEntries()]);
    }
}
