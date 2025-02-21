<?php

namespace App\Livewire;

use Livewire\Component;

class KeySelect extends Component
{
    public string $zone = '';
    public bool $isForm = false;

    public function render()
    {
        return view('livewire.key-select');
    }
}
