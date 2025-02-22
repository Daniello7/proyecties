<?php

namespace App\Livewire;

use App\Models\KeyControl;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
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
    public bool $isHomeView = false;
    public int $key_id;
    protected $listeners = ['keyUpdated' => 'updateKeyId'];

    public function mount(): void
    {
        if ($this->isHomeView) {
            $this->configureKeyControlHomeView();
        } else {
            $this->configureKeyControlIndexView();
        }
    }

    public function updateKeyId($newKeyId): void
    {
        $this->key_id = $newKeyId;
    }

    public function configureKeyControlIndexView(): void
    {
        $this->columns = ['Person', 'Key', 'Deliver', 'Exit', 'Receiver', 'Entry', 'Comment', 'Actions'];
        if (isset($this->key_id)) array_splice($this->columns, 1, 1);
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

    public function configureKeyControlHomeView(): void
    {
        $this->columns = ['Person', 'Key', 'Comment', 'Actions'];
        $this->select = [
            'key_controls.id',
            'key_controls.person_id',
            'key_controls.key_id',
            'key_controls.comment',
        ];
        $this->columnMap = [
            'Key' => 'key.name',
            'Person' => 'person.name',
            'Comment' => null,
            'Actions' => null
        ];
        $this->relations = [
            'key',
            'person:id,name,last_name',
        ];
        $this->sortColumn = 'person.name';
        $this->sortDirection = 'asc';
    }

    public function getKeyControlIndexView(): Collection // Últimos registros
    {
        $query = KeyControl::query()
            ->with($this->relations)
            ->select($this->select)
            ->join('keys as key', 'key.id', '=', 'key_controls.key_id')
            ->join('people as person', 'person.id', '=', 'key_controls.person_id')
            ->join('users as deliver', 'deliver.id', '=', 'key_controls.deliver_user_id')
            ->join('users as receiver', 'receiver.id', '=', 'key_controls.receiver_user_id');


        $this->applySearchFilter($query);

        if (isset($this->key_id)) {
            $query->where('key.id', $this->key_id);
        } else {
            $query->where('entry_time', '>=', now()->subMonths(2));
        }
        return $query->orderBy($this->sortColumn, $this->sortDirection)
            ->whereNotNull('entry_time')->get();
    }

    public function getKeyControlHomeView(): Collection// Llaves que se encuentran fuera
    {
        $query = KeyControl::query()
            ->with($this->relations)
            ->select($this->select)
            ->join('keys as key', 'key.id', '=', 'key_controls.key_id')
            ->join('people as person', 'person.id', '=', 'key_controls.person_id')
            ->whereNull('entry_time');

        $this->applySearchFilter($query);

        return $query->orderBy($this->sortColumn, $this->sortDirection)->get();
    }

    public function getKeyControlRows(): Collection
    {
        if ($this->isHomeView)
            return $this->getKeyControlHomeView();

        return $this->getKeyControlIndexView();
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
        return view('livewire.key-control-table', [
            'rows' => $this->getKeyControlRows()
        ]);
    }
}
