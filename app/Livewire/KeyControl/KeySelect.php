<?php

namespace App\Livewire\KeyControl;

use Livewire\Component;

class KeySelect extends Component
{
    public string $zone = '';
    public int $key_id = 0;
    public bool $isForm = false;

    public function updatedKeyId($value): void
    {
        $this->dispatch('keyUpdated', $value);
    }

    public function render()
    {
        return view('livewire.key-control.key-select');
    }
}
