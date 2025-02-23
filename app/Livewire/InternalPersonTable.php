<?php

namespace App\Livewire;

use App\Models\InternalPerson;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class InternalPersonTable extends Component
{
    use WithPagination;

    public array $columns;
    public array $select;
    public string $sortColumn;
    public string $sortDirection;
    public array $columnMap;
    public string $search = '';

    public function mount()
    {
        $this->configureInternalPersonIndexView();
    }

    public function configureInternalPersonIndexView(): void
    {
        $this->columns = ['Nº Employer', 'Name', 'Last Name', 'Actions'];
        $this->select = [
            'internal_people.id',
            'person.name',
            'person.last_name',
        ];
        $this->sortColumn = 'internal_people.id';
        $this->sortDirection = 'asc';
        $this->columnMap = [
            'Nº Employer' => 'internal_people.id',
            'Name' => 'person.name',
            'Last Name' => 'person.last_name',
            'Actions' => null,
        ];
    }

    public function getInternalPeople(): Collection
    {
        $query = InternalPerson::query()
            ->select($this->select)
            ->join('people as person', 'person.id', '=', 'internal_people.person_id');

        $this->applySearchFilter($query);

        return $query
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->get();
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

    public function render()
    {
        return view('livewire.internal-person-table', ['internalPeople' => $this->getInternalPeople()]);
    }
}
