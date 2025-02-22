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
    public int $key_id;
    protected $listeners = ['keyUpdated' => 'updateKeyId'];

    public function mount()
    {
        $this->configureKeyControlIndexView();
    }

    public function updateKeyId($newKeyId)
    {
        $this->key_id = $newKeyId;
    }

    public function configureKeyControlIndexView()
    {
        $this->columns = ['Key', 'Person', 'Deliver', 'Exit', 'Receiver', 'Entry', 'Comment', 'Actions'];
        $this->select = ['key_controls.*'];
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
            ->select($this->select)
            ->join('keys as key', 'key.id', '=', 'key_controls.key_id')
            ->join('people as person', 'person.id', '=', 'key_controls.person_id')
            ->join('users as deliver', 'deliver.id', '=', 'key_controls.deliver_user_id')
            ->join('users as receiver', 'receiver.id', '=', 'key_controls.receiver_user_id')
            ->whereNotNull('entry_time');

        $this->applySearchFilter($query);

        if ($this->sortColumn) {
            $query->orderBy($this->sortColumn, $this->sortDirection);
        }

        if (isset($this->key_id)) {
            $query->where('key.id', $this->key_id);
        } else {
            $query->where('entry_time', '>=', now()->subMonths(2));
        }
        return $query->get();
    }

    public function applySearchFilter($query)
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
