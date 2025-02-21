<?php

namespace App\View\Components;

use App\Models\Key;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class KeySelect extends Component
{
    public object $keys;

    public function __construct($zone = '')
    {
        $this->keys = $this->getKeysByZone($zone);
    }

    public function getKeysByZone($zone = ''): object
    {
        return Key::where('zone', $zone)->get();
    }

    public function render(): View
    {
        return view('components.key-select', ['keys' => $this->keys]);
    }
}
