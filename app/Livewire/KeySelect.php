<?php

namespace App\Livewire;

use Livewire\Component;

class KeySelect extends Component
{
    public string $zone = '';
    public int $key_id = 0;
    public bool $isForm = false;

    public function updatedKeyId($value)
    {
        $this->dispatch('keyUpdated', $value);
    }

    public function render()
    {
        return view('livewire.key-select');
    }
}
