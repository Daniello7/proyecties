<?php

namespace App\View\Components;

use App\Models\Person;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PersonSelect extends Component
{
    public object $people;

    public function __construct(public string $oldContact = '', public bool $includeExternal = false)
    {
        $query = Person::query()
            ->with('internalPerson');

        if (!$includeExternal) {
            $query->whereHas('internalPerson');
        }

        $this->people = $query->get();

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.person-select');
    }
}
