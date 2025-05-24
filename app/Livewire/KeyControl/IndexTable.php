<?php

namespace App\Livewire\KeyControl;

use App\Http\Requests\KeyControl\UpdateKeyControlRequest;
use App\Models\KeyControl;
use App\Traits\HasTableEloquent;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class IndexTable extends Component
{
    use HasTableEloquent;

    public ?int $keyId = null;
    public ?int $areaId = null;
    public ?int $exitKey_id;
    public ?KeyControl $exitKey;
    public $activeModal = null;

    public UpdateKeyControlRequest $formRequest;

    // Form properties
    public $person_id;
    public $key_id;
    public $deliver_id;
    public $receiver_id;
    public $entry_time;
    public $exit_time;
    public $comment;

    protected $listeners = [
        'keyUpdated' => 'updateKeyId',
        'zoneUpdated' => 'resetKeyId'
    ];

    public function mount(): void
    {
        $this->configureKeyControlIndexView();
    }

    public function updateKeyId($newKeyId): void
    {
        $this->keyId = $newKeyId;
    }

    public function resetKeyId($newAreaId): void
    {
        $this->areaId = $newAreaId;

        $this->keyId = null;
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

    public function getKeyControlRows(): LengthAwarePaginator
    {
        $query = KeyControl::query()
            ->with($this->relations)
            ->select($this->select)
            ->joinRelations();

        $this->applySearchFilter($query);

        if (isset($this->areaId)) {
            $query->where('key.area_id', $this->areaId);
        }

        if (isset($this->keyId)) {
            $query->where('key.id', $this->keyId);
        }

        return $query->orderBy($this->sortColumn, $this->sortDirection)
            ->whereNotNull('entry_time')->paginate(20);
    }

    public function openModal($modal, $id): void
    {
        $this->activeModal = $modal;
        $this->exitKey_id = $id;

        if ($modal === 'editKeyControl') {
            $this->exitKey = KeyControl::findOrFail($id);
            $this->loadKeyControlData();
        }
    }

    public function closeModal(): void
    {
        $this->resetExceptConfig(['keyId']);
    }

    private function loadKeyControlData(): void
    {
        $this->person_id = $this->exitKey->person_id;
        $this->key_id = $this->exitKey->key_id;
        $this->deliver_id = $this->exitKey->deliver_id;
        $this->receiver_id = $this->exitKey->receiver_id;
        $this->entry_time = substr($this->exitKey->entry_time, 0, -3);
        $this->exit_time = substr($this->exitKey->exit_time, 0, -3);
    }

    public function updateKeyControl(): void
    {
        $this->formRequest = new UpdateKeyControlRequest();

        $validated = $this->validate($this->formRequest->rules());

        $this->exitKey->update($validated);

        session()->flash('key-status', __('messages.key-control_updated'));

        $this->closeModal();
    }

    public function deleteKeyControl($id): void
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
