<?php

namespace App\Livewire;

use App\Models\Person;
use App\Traits\HasTableEloquent;
use Livewire\Component;
use Livewire\WithPagination;

class PersonTable extends Component
{
    use WithPagination, HasTableEloquent;

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

    public function render()
    {
        return view('livewire.person-table', ['rows' => $this->getPeople()]);
    }
}
