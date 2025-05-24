<?php

namespace App\Livewire\KeyControl;

use Livewire\Component;

class KeySelect extends Component
{
    public int $zone;
    public ?int $keyId = 0;

    public function updatedKeyId($keyId): void
    {
        $this->dispatch('keyUpdated', $keyId);
    }

    public function updatedZone($zone): void
    {
        $this->zone = $zone;
        $this->keyId = 0;
        $this->dispatch('zoneUpdated', $zone);
    }

    public function render()
    {
        return view('livewire.key-control.key-select');
    }
}
