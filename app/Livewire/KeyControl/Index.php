<?php

namespace App\Livewire\KeyControl;

use App\Models\KeyControl;
use Livewire\Component;

class Index extends Component
{
    public ?int $areaId;
    public ?int $key_id = null;
    public ?int $person_id = null;
    public string $comment = '';

    protected $rules = [
        'key_id' => 'required|integer|exists:keys,id',
        'person_id' => 'required|integer|exists:people,id',
        'comment' => 'string|max:255'
    ];

    public bool $openedExitKeysTable = true;
    public bool $openedCreateExitKey = false;
    public bool $openedSearchKey = false;

    public bool $openedKeyTable = false;

    public function openExitKeysTable(): void
    {
        $this->closeWindows();
        $this->openedExitKeysTable = true;
    }

    public function openCreateExitKey(): void
    {
        $this->closeWindows();
        $this->openedCreateExitKey = true;
    }

    public function openSearchKey(): void
    {
        $this->closeWindows();
        $this->openedSearchKey = true;
    }

    public function openKeyTable(): void
    {
        $this->closeWindows();
        $this->openedKeyTable = true;
    }

    private function closeWindows(): void
    {
        $this->openedExitKeysTable = false;
        $this->openedCreateExitKey = false;
        $this->openedSearchKey = false;
        $this->openedKeyTable = false;
    }

    public function updatedAreaId(): void
    {
        $this->reset('key_id');
    }

    public function storeExitKey(): void
    {
        $validated = $this->validate();

        $validated['exit_time'] = now();
        $validated['deliver_user_id'] = auth()->user()->id;

        KeyControl::create($validated);

        session()->flash('key-status', __('messages.key-control_created'));

        $this->reset();
    }

    public function render()
    {
        return view('livewire.key-control.index');
    }
}
