<?php

namespace App\Livewire;

use App\Models\Person;
use Livewire\Component;
use Livewire\WithPagination;

class PersonTable extends Component
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
        $this->configurePersonView();
    }

    public function configurePersonView()
    {
        $this->columns = ['DNI', 'Name', 'Last Name', 'Company', 'Actions'];
        $this->select = ['id', 'document_number', 'name', 'last_name', 'company'];
        $this->sortColumn = 'name';
        $this->sortDirection = 'asc';
        $this->columnMap = [
            'DNI' => 'document_number',
            'Name' => 'name',
            'Last Name' => 'last_name',
            'Company' => 'company',
            'Actions' => null,
        ];
    }

    public function getPeople()
    {
        $query = Person::query()
            ->select($this->select)
            ->whereDoesntHave('InternalPerson');

        $this->applySearchFilter($query);

        return $query
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(50);
    }

    public function applySearchFilter($query)
    {
        if (!$this->search) return;

        $query->where(function ($q) {
            foreach ($this->columnMap as $key => $column) {
                if ($column) {
                    $q->orWhere($column, 'LIKE', "%{$this->search}%");
                }
            }
        });
    }

    public function sortBy($column)
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
//        dd($this->getPeople());
        return view('livewire.person-table', ['rows' => $this->getPeople()]);
    }
}
