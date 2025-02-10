<?php

namespace App\View\Components;

use App\Models\InternalPerson;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PersonSelect extends Component
{
    public function __construct(public $oldContact = '', public $internalPeople = [])
    {
        $this->internalPeople = InternalPerson::query()
            ->with('person:id,name,last_name')
            ->select(['id', 'person_id'])->get();

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.person-select');
    }
}
