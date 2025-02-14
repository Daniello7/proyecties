<?php

namespace App\Livewire;

use App\Models\PersonEntry;
use Livewire\Component;
use Livewire\WithPagination;

class PersonEntryTable extends Component
{
    use WithPagination;

    public array $columns = ['Name', 'Company', 'Contact', 'Comment', 'Actions'];
    public array $select = ['id', 'person_id', 'internal_person_id', 'comment_id', 'entry_time'];
    private string $sortColumn = 'arrival_time';
    private string $sortDirection = 'asc';
    private string $info = '';

    public function mount(string $info = '')
    {
        $this->info = $info;
    }

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $rows = [];

        $relations = [
            'person:id,name,last_name,company',
            'internalPerson:id,person_id',
            'internalPerson.person:id,name,last_name',
            'comment:id,content'
        ];

        if ($this->info === 'last_entries') {
            $this->columns[3] = 'Latest Visit';
            $this->select[3] = 'exit_time';
            $relations[0] .= ',document_number';
            array_pop($relations);
            array_unshift($this->columns, 'DNI');

            $latestEntries = PersonEntry::query()
                ->selectRaw('person_id as group_person_id, MAX(exit_time) as latest_exit_time')
                ->whereNotNull('exit_time')
                ->groupBy('person_id');

            $rows = PersonEntry::query()
                ->with($relations)
                ->select($this->select)
                ->joinSub($latestEntries, 'latest_entries', function ($join) {
                    $join->on('person_entries.person_id', '=', 'latest_entries.group_person_id')
                        ->on('person_entries.exit_time', '=', 'latest_entries.latest_exit_time');
                })
                ->orderByDesc('exit_time')
                ->paginate(20);
        } else {
            $rows = PersonEntry::query()
                ->with($relations)
                ->select($this->select)
                ->whereNull('exit_time')
                ->orderBy('arrival_time')
                ->paginate(20);
        }

        return view('livewire.person-entry-table', compact('rows'));
    }
}
