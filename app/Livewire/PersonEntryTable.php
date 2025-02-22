<?php

namespace App\Livewire;

use App\Models\Person;
use App\Models\PersonEntry;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class PersonEntryTable extends Component
{
    use WithPagination;

    public string $header;
    public array $columns;
    public array $columnMap;
    public array $select;
    public string $sortColumn;
    public string $sortDirection;
    public array $relations;
    public string $info;
    public int $person_id;
    public string $search = '';

    public function mount(string $info = '', int $person_id = 0): void
    {
        $this->person_id = $person_id;
        $this->info = $info;
        $this->applyTableConfiguration();
    }

    public function applyTableConfiguration(): void
    {
        // Default configuration
        $this->configureActiveEntriesView();

        switch ($this->info) {
            case 'latest_entries':
                $this->configureLastEntriesView();
                break;
            case 'show':
                $this->configurePersonEntriesView();
                break;
        }
    }

    public function configureActiveEntriesView(): void
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

    private function configureLastEntriesView(): void
    {
        $this->columns[3] = 'Latest Visit';
        $this->select[3] = 'exit_time';
        $this->relations[0] .= ',document_number';
        $this->sortColumn = 'exit_time';
        $this->sortDirection = 'desc';
        $this->columnMap['Latest Visit'] = 'exit_time';
        $this->columnMap['DNI'] = 'person.document_number';
        array_unshift($this->columns, 'DNI');
        array_pop($this->relations);
    }

    private function configurePersonEntriesView(): void
    {
        array_splice($this->columns, 0, 2);
        array_splice($this->columns, 1, 0, ['Reason', 'Porter', 'Arrival', 'Entry', 'Exit']);
        array_splice($this->select, 1, 1);
        array_splice($this->select, 2, 0, ['person_entries.user_id', 'reason', 'arrival_time', 'exit_time']);
        $this->sortColumn = 'exit_time';
        $this->sortDirection = 'desc';
        $this->relations[0] = 'user:id,name';
        $this->columnMap['Reason'] = 'person_entries.reason';
        $this->columnMap['Porter'] = 'person_entries.user_id';
        $this->columnMap['Arrival'] = 'arrival_time';
        $this->columnMap['Entry'] = 'entry_time';
        $this->columnMap['Exit'] = 'exit_time';
        array_splice($this->columnMap, 0, 2);
    }

    public function sortBy($column): void
    {
        if (!$this->columnMap[$column]) return;

        $column = $this->columnMap[$column];

        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    private function getEntries(): LengthAwarePaginator
    {
        if ($this->info === 'latest_entries')
            return $this->getLatestEntries();

        if ($this->info === 'show')
            return $this->getPersonEntries();

        return $this->getActiveEntries();
    }

    private function getActiveEntries(): LengthAwarePaginator
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

    private function getLatestEntries(): LengthAwarePaginator
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
            ->join('people as person', 'person_entries.person_id', '=', 'person.id')
            ->join('internal_people as internalPerson',
                'person_entries.internal_person_id', '=', 'internalPerson.id')
            ->join('people as internalPerson_personRelation',
                'internalPerson.person_id', '=', 'internalPerson_personRelation.id')
            ->whereNotNull('exit_time');

        $this->applySearchFilter($query);

        return $query
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(20);
    }

    private function getPersonEntries(): LengthAwarePaginator
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

    public function applySearchFilter($query): void
    {
        if (!$this->search) return;

        $query->where(function ($q) {
            foreach ($this->columnMap as $column) {
                if ($column) {
                    $q->orWhere($column, 'LIKE', "%{$this->search}%");
                }
            }
        });
    }

    public function updateEntry(int $id): void
    {
        $personEntry = PersonEntry::find($id);

        $personEntry->update(['entry_time' => Carbon::now()]);

        session()->flash('success', 'Entry updated successfully.');
    }

    public function updateExit(int $id): void
    {
        $personEntry = PersonEntry::find($id);

        $personEntry->update(['exit_time' => Carbon::now()]);

        session()->flash('success', 'Exit updated successfully.');
    }

    public function destroyPersonEntry(int $id): void
    {
        PersonEntry::destroy($id);
        session()->flash('success', 'Person entry deleted.');

    }

    public function render()
    {
        return view('livewire.person-entry-table', [
            'rows' => $this->getEntries(),
        ]);
    }
}
