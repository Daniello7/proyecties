<?php

namespace App\Livewire\KeyControl;

use App\Traits\HasTableEloquent;
use Livewire\Component;

class KeyTable extends Component
{
    use HasTableEloquent;

    public function mount(): void
    {
        $this->columns = ['Name', 'Zone', 'Actions'];
        $this->select = ['id', 'name', 'zone'];
        $this->columnMap = [
            'Name' => 'name',
            'Zone' => 'zone',
            'Actions' => null
        ];
        $this->sortColumn = 'zone';
        $this->sortDirection = 'asc';
    }

    public function render()
    {
        return view('livewire.key-table');
    }
}
