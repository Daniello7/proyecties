<?php

namespace App\Livewire;

use App\Models\KeyControl;
use Livewire\Component;

class KeyControlTable extends Component
{
    public array $columns;
    public array $select;
    public array $columnMap;
    public array $relations;
    public string $sortColumn;
    public string $sortDirection;
    public string $search = '';

    public function mount()
    {
        $this->configureKeyControlIndexView();
    }

    public function configureKeyControlIndexView()
    {
        $this->columns = ['Key', 'Person', 'Deliver', 'Exit', 'Receiver', 'Entry', 'Comment', 'Actions'];
        $this->select = ['id', 'key_id', 'person_id', 'deliver_user_id', 'exit_time', 'receiver_user_id', 'entry_time', 'comment'];
        $this->columnMap = [
            'Key' => 'key.name',
            'Person' => 'person.name',
            'Deliver' => 'deliver.name',
            'Receiver' => 'receiver.name',
            'Exit' => 'exit_time',
            'Entry' => 'entry_time',
            'Comment' => null,
            'Actions' => null
        ];
        $this->relations = [
            'key',
            'person:id,name,last_name',
            'deliver:id,name',
            'receiver:id,name',
        ];
        $this->sortColumn = 'entry_time';
        $this->sortDirection = 'desc';
    }

    public function getKeyControlIndexView() // Últimos registros
    {
        $query = KeyControl::query()
            ->with($this->relations)
            ->select($this->select);

        $this->applySearchFilter($query);

        return $query
            ->whereNotNull('entry_time')
            ->where('entry_time', '>=', now()->subDays(14))
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->get();
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
        return view('livewire.key-control-table', [
            'rows' => $this->getKeyControlIndexView()
        ]);
    }
}
