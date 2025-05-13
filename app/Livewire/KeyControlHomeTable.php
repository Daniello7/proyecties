<?php

namespace App\Livewire;

use App\Models\KeyControl;
use App\Traits\HasTableEloquent;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class KeyControlHomeTable extends Component
{
    use HasTableEloquent;

    public function mount(): void
    {
        $this->configureKeyControlHomeView();
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
        $this->sortColumn = 'key_controls.created_at';
        $this->sortDirection = 'asc';
    }

    public function getKeyControlRows(): Collection
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

    public function updateKeyControlReception($id): void
    {
        $keyControl = KeyControl::findOrFail($id);

        $keyControl->update([
            'entry_time' => now(),
            'receiver_user_id' => auth()->user()->id,
        ]);

        session()->flash('key-status', __('messages.key-control_updated'));
    }

    public function deleteKeyControlRecord($id): void
    {
        $keyControl = KeyControl::findorFail($id);

        $keyControl->delete();

        session()->flash('key-status', __('messages.key-control_deleted'));
    }

    public function render()
    {
        return view('livewire.key-control-home-table', [
            'rows' => $this->getKeyControlRows()
        ]);
    }
}
