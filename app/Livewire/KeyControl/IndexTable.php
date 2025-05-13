<?php

namespace App\Livewire\KeyControl;

use App\Models\KeyControl;
use App\Traits\HasTableEloquent;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class IndexTable extends Component
{
    use HasTableEloquent;

    public int $key_id;
    protected $listeners = ['keyUpdated' => 'updateKeyId'];

    public function mount(): void
    {
        $this->configureKeyControlIndexView();
    }

    public function updateKeyId($newKeyId): void
    {
        $this->key_id = $newKeyId;
    }

    public function configureKeyControlIndexView(): void
    {
        $this->columns = ['Person', 'Key', 'Deliver', 'Exit', 'Receiver', 'Entry', 'Comment', 'Actions'];
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

    public function getKeyControlRows(): Collection
    {
        $query = KeyControl::query()
            ->with($this->relations)
            ->select($this->select)
            ->joinRelations();

        $this->applySearchFilter($query);

        if (isset($this->key_id)) {
            $query->where('key.id', $this->key_id);
        } else {
            $query->where('entry_time', '>=', now()->subMonths(2));
        }

        return $query->orderBy($this->sortColumn, $this->sortDirection)
            ->whereNotNull('entry_time')->get();
    }

    public function deleteKeyControlRecord($id): void
    {
        $keyControl = KeyControl::findorFail($id);

        $keyControl->delete();

        session()->flash('key-status', __('messages.key-control_deleted'));
    }

    public function render()
    {
        return view('livewire.key-control.index-table', [
            'rows' => $this->getKeyControlRows()
        ]);
    }
}
