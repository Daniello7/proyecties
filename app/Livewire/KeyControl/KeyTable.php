<?php

namespace App\Livewire\KeyControl;

use App\Models\Area;
use App\Models\Key;
use App\Traits\HasTableEloquent;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class KeyTable extends Component
{
    use HasTableEloquent;

    public $activeModal = null;
    public $key_id;
    public $key;

    // Form properties
    public $area_id;
    public $name;

    protected $rules = [
        'area_id' => 'required|integer|exists:areas,id',
        'name' => 'required|string|max:255'
    ];

    public function mount(): void
    {
        $this->columns = ['Area', 'Key', 'Actions'];
        $this->select = ['id', 'area_key_number', 'name', 'area_id'];
        $this->columnMap = [
            'Area' => 'area_id',
            'Key' => 'name',
            'Actions' => null
        ];
    }

    public function getKeys(): Collection
    {
        $query = Key::with('area')
            ->select($this->select)
            ->orderBy('area_id')
            ->orderBy('area_key_number');

        $this->applySearchFilter($query);

        return $query->get();
    }

    public function openModal(string $modal, ?int $id = null): void
    {
        $this->activeModal = $modal;
        $this->key_id = $id;

        if ($this->activeModal == 'editKey') {
            $this->key = Key::findOrFail($id);
            $this->loadKeyData();
        }
    }

    public function closeModal(): void
    {
        $this->resetExceptConfig();
    }

    private function loadKeyData(): void
    {
        $this->area_id = $this->key->area_id;
        $this->name = $this->key->name;
    }

    public function storeKey(): void
    {
        $validated = $this->validate();

        Area::findOrFail($this->area_id)->keys()->create($validated);

        session()->flash('key-status', __('messages.key_created'));

        $this->closeModal();
    }

    public function updateKey(): void
    {
        $validated = $this->validate();

        Key::findOrFail($this->key_id)->update(['name' => $validated['name']]);

        session()->flash('key-status', __('messages.key_updated'));

        $this->closeModal();
    }

    public function deleteKey(): void
    {
        Key::findOrFail($this->key_id)->delete();

        session()->flash('key-status', __('messages.key_deleted'));

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.key-table',
            ['rows' => $this->getKeys()]);
    }
}
