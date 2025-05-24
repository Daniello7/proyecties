<?php

namespace App\View\Components;

use App\Models\Key;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class KeySelect extends Component
{
    public object $keys;

    public function __construct(?int $areaId = null)
    {
        $this->keys = $this->getKeysByArea($areaId);
    }

    public function getKeysByArea(?int $areaId = null): object
    {
        return Key::where('area_id', $areaId)->get();
    }

    public function render(): View
    {
        return view('components.key-select', ['keys' => $this->keys]);
    }
}
