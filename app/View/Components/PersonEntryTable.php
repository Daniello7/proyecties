<?php

namespace App\View\Components;

use Closure;
use App\Models\PersonEntry;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PersonEntryTable extends Component
{
    public $rows;
    public $columns = ['Name', 'Company', 'Contact', 'Reason', 'Comment', 'Actions'];
    public $select = ['person_id', 'internal_person_id', 'reason', 'comment_id', 'entry_time'];

    public function __construct(string $info = '')
    {
        $relations = [
            'person:id,name,last_name,company',
            'internalPerson:id,person_id',
            'internalPerson.person:id,name,last_name',
            'comment:id,content'
        ];

        if ($info === 'last_entries') {
            array_unshift($this->columns, 'Porter', 'Arrival', 'Entry', 'Exit');
            array_unshift($this->select, 'user_id', 'arrival_time', 'exit_time');
            array_unshift($relations, 'user:id,name');

            $latestEntries = PersonEntry::query()
                ->selectRaw('person_id as group_person_id, MAX(exit_time) as latest_exit_time')
                ->whereNotNull('exit_time')
                ->groupBy('person_id');

            $this->rows = PersonEntry::query()
                ->with($relations)
                ->select($this->select)
                ->joinSub($latestEntries, 'latest_entries', function ($join) {
                    $join->on('person_entries.person_id', '=', 'latest_entries.group_person_id')
                        ->on('person_entries.exit_time', '=', 'latest_entries.latest_exit_time');
                })
                ->orderByDesc('exit_time')
                ->paginate(20);
        } else {
            $this->rows = PersonEntry::query()
                ->with($relations)
                ->select($this->select)
                ->whereNull('exit_time')
                ->orderBy('arrival_time')
                ->get();
        }


    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.person-entry-table');
    }
}
