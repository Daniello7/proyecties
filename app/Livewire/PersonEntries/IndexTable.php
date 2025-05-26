<?php

namespace App\Livewire\PersonEntries;

use App\Http\Requests\PersonEntry\UpdatePersonEntryRequest;
use App\Models\Person;
use App\Models\PersonEntry;
use App\Traits\HasLoadPersonEntryData;
use App\Traits\HasPersonEntryStore;
use App\Traits\HasTableEloquent;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class IndexTable extends Component
{
    use HasTableEloquent, HasLoadPersonEntryData, HasPersonEntryStore;

    public ?string $activeModal = null;
    public ?int $id = null;
    public ?Person $person = null;

    public function mount(): void
    {
        $this->columns = ['DNI', 'Name', 'Company', 'Contact', 'Latest Visit', 'Reason', 'Comment', 'Actions'];
        $this->select = [
            'person_entries.id',
            'person_entries.person_id',
            'person_entries.internal_person_id',
            'person_entries.reason',
            'person_entries.exit_time',
            'person_entries.comment',
            'entry_time'
        ];
        $this->relations = [
            'person:id,name,last_name,company,document_number',
            'internalPerson:id,person_id',
        ];
        $this->sortColumn = 'exit_time';
        $this->sortDirection = 'desc';
        $this->columnMap = [
            'DNI' => 'person.document_number',
            'Name' => 'person.name',
            'Company' => 'person.company',
            'Contact' => 'internalPerson_personRelation.name',
            'Latest Visit' => 'exit_time',
            'Reason' => 'reason',
            'Comment' => null,
            'Actions' => null,
        ];
    }

    private function getEntries(): LengthAwarePaginator
    {
        $externalPeople = Person::query()
            ->select('id')
            ->doesntHave('internalPerson');

        $latestEntries = PersonEntry::query()
            ->selectRaw('MAX(person_entries.id)')
            ->wherein('person_entries.person_id', $externalPeople)
            ->groupBy('person_entries.person_id');

        $query = PersonEntry::query()
            ->with($this->relations)
            ->select($this->select)
            ->whereIn('person_entries.id', $latestEntries)
            ->joinInternalPerson(true)
            ->whereNotNull('exit_time');

        $this->applySearchFilter($query);

        return $query
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(20);
    }

    public function openModal($modal, int $id): void
    {
        $this->id = $id;
        $this->activeModal = $modal;

        if ($modal === 'editEntry') {
            $this->entry = PersonEntry::with(['person', 'internalPerson.person'])->find($id);
            $this->loadPersonEntryData();
        }

        if ($modal === 'createEntry') {
            $this->person = Person::with(['personEntries' => function ($q) {
                $q->orderBy('exit_time', 'desc')->first();
            }])->find($id);

            $this->entry = $this->person->personEntries->first();

            $this->loadPersonEntryData();
            $this->comment = null;
        }
    }

    public function closeModal(): void
    {
        $this->resetExceptConfig();
    }

    public function updatePersonEntry(): void
    {
        $this->formRequest = new UpdatePersonEntryRequest();

        $validated = $this->validate($this->formRequest->rules());

        if ($validated['entry_time'] == '') $validated['entry_time'] = null;

        $this->entry->update($validated);

        session()->flash('success', __('messages.person-entry_updated'));

        $this->closeModal();
    }

    public function destroyPersonEntry(int $id): void
    {
        PersonEntry::destroy($id);

        session()->flash('success', __('messages.person-entry_deleted'));

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.person-entries.index-table', ['rows' => $this->getEntries()]);
    }
}
