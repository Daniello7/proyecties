<?php

namespace App\Livewire\PersonEntries;

use App\Jobs\GenerateActiveEntriesPdfJob;
use App\Models\Person;
use App\Models\PersonEntry;
use App\Traits\HasTableEloquent;
use Livewire\Component;

class HomeTable extends Component
{
    use HasTableEloquent;

    public function mount()
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
            ->joinInternalPerson(true)
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

    public function generateActiveEntriesPdf(): void
    {
        GenerateActiveEntriesPdfJob::dispatch(
            $this->columns,
            $this->getEntries()->pluck('id')->toArray(),
            auth()->user()->id,
        );
    }

    public function render()
    {
        return view('livewire.person-entries.home-table', ['rows' => $this->getEntries()]);
    }
}