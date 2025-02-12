<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public array $links;

    public function __construct()
    {
        $this->links = [
            ['name' => 'Home', 'url' => 'control-access'],
            ['name' => 'External Staff', 'url' => 'person-entries.index'],
            ['name' => 'Internal Staff', 'url' => 'internal-staff.index'],
            ['name' => 'Package', 'url' => 'package.index'],
            ['name' => 'Key control', 'url' => 'key-control.index'],
            ['name' => 'Log Out', 'url' => 'logout'],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar');
    }
}
