<?php

namespace App\Livewire;

use App\Models\InternalPerson;
use App\Traits\HasTableEloquent;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class InternalPersonTable extends Component
{
    use HasTableEloquent;

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

    public function render()
    {
        return view('livewire.internal-person-table', ['internalPeople' => $this->getInternalPeople()]);
    }
}
