<?php

namespace App\Livewire;

use Livewire\Component;

class KeyControlCreate extends Component
{
    public string $zone = '';

    public function render()
    {
        return view('livewire.key-control-create');
    }
}
