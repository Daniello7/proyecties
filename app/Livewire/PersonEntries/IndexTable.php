<?php

namespace App\Livewire\PersonEntries;

use App\Models\Person;
use App\Models\PersonEntry;
use App\Traits\HasTableEloquent;
use Livewire\Component;

class IndexTable extends Component
{
    use HasTableEloquent;

    public function mount()
    {
        $this->columns = ['DNI', 'Name', 'Company', 'Contact', 'Latest Visit', 'Actions'];
        $this->select = [
            'person_entries.id',
            'person_entries.person_id',
            'person_entries.internal_person_id',
            'person_entries.exit_time',
            'entry_time'
        ];
        $this->relations = [
            'person:id,name,last_name,company,document_number',
            'internalPerson:id,person_id',
        ];
        $this->sortColumn = 'exit_time';
        $this->sortDirection = 'desc';
        $this->columnMap = [
            'DNI' => 'person.document_number',
            'Name' => 'person.name',
            'Company' => 'person.company',
            'Contact' => 'internalPerson_personRelation.name',
            'Latest Visit' => 'exit_time',
            'Comment' => null,
            'Actions' => null,
        ];
    }

    private function getEntries()
    {
        $externalPeople = Person::query()
            ->select('id')
            ->doesntHave('internalPerson');

        $latestEntries = PersonEntry::query()
            ->selectRaw('MAX(person_entries.id)')
            ->wherein('person_entries.person_id', $externalPeople)
            ->groupBy('person_entries.person_id');

        $query = PersonEntry::query()
            ->with($this->relations)
            ->select($this->select)
            ->whereIn('person_entries.id', $latestEntries)
            ->joinInternalPerson(true)
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
        return view('livewire.person-entries.index-table', ['rows' => $this->getEntries()]);
    }
}
