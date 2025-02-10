<?php

namespace App\View\Components;

use App\Models\PersonEntry;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ReasonSelect extends Component
{
    public $reasons = PersonEntry::REASONS;

    public $oldReason;

    public function __construct($oldReason = null)
    {
        $this->oldReason = $oldReason;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.reason-select');
    }
}
