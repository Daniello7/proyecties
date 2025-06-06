<?php

namespace App\Livewire;

use App\Http\Requests\Person\StorePersonRequest;
use App\Http\Requests\Person\UpdatePersonRequest;
use App\Models\Person;
use App\Models\PersonEntry;
use App\Traits\HasLoadPersonEntryData;
use App\Traits\HasPersonEntryStore;
use App\Traits\HasTableEloquent;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class PersonTable extends Component
{
    use WithPagination, HasTableEloquent, HasLoadPersonEntryData, HasPersonEntryStore;

    public ?string $activeModal = null;
    public ?int $id = null;
    public ?Person $person = null;
    public ?PersonEntry $entry = null;

    // Form properties
    public $document_number;
    public $name;
    public $last_name;
    public $company;

    public function mount()
    {
        $this->configurePersonView();
    }

    public function openModal($modal, int $id = null): void
    {
        $this->id = $id;
        $this->activeModal = $modal;

        if ($modal === 'editPerson') {
            $this->person = Person::findOrFail($id);

            $this->authorize('update', $this->person);

            $this->loadPersonData();
        }

        if ($modal === 'createEntry') {
            $this->authorize('create', PersonEntry::class);

            $this->person = Person::with(['personEntries' => function ($q) {
                $q->orderBy('exit_time', 'desc')->first();
            }])->find($id);

            $this->entry = $this->person->personEntries->first();

            $this->loadPersonData();

            if ($this->entry) {
                $this->loadPersonEntryData();
                $this->comment = null;
            }
        }
    }

    public function closeModal(): void
    {
        $this->resetExceptConfig();
    }

    private function loadPersonData(): void
    {
        $this->person_id = $this->person->person_id;
        $this->document_number = $this->person->document_number;
        $this->name = $this->person->name;
        $this->last_name = $this->person->last_name;
        $this->company = $this->person->company;
        $this->comment = $this->person->comment;
    }

    public function storePerson(): void
    {
        $this->authorize('create', Person::class);

        $this->formRequest = new StorePersonRequest();

        $validated = $this->validate($this->formRequest->rules());

        Person::create($validated);

        session()->flash('person-status', __('messages.person_created'));

        $this->closeModal();
    }

    public function updatePerson(): void
    {
        $this->authorize('update', $this->person);

        $this->formRequest = new UpdatePersonRequest();

        $validated = $this->validate($this->formRequest->rules());

        $this->person->update($validated);

        session()->flash('person-status', __('messages.person_updated'));

        $this->closeModal();
    }

    public function deletePerson(): void
    {
        $person = Person::findOrFail($this->id);

        $this->authorize('delete', $person);

        $person->delete();

        session()->flash('person-status', __('messages.person_deleted'));

        $this->closeModal();
    }

    public function configurePersonView(): void
    {
        $this->columns = ['DNI', 'Name', 'Last Name', 'Company', 'Actions'];
        $this->select = ['id', 'document_number', 'name', 'last_name', 'company'];
        $this->sortColumn = 'name';
        $this->sortDirection = 'asc';
        $this->columnMap = [
            'DNI' => 'document_number',
            'Name' => 'name',
            'Last Name' => 'last_name',
            'Company' => 'company',
            'Actions' => null,
        ];
    }

    public function getPeople(): LengthAwarePaginator
    {
        $query = Person::query()
            ->select($this->select)
            ->whereDoesntHave('InternalPerson');

        $this->applySearchFilter($query);

        return $query
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(30);
    }

    public function render()
    {
        return view('livewire.person-table', ['rows' => $this->getPeople()]);
    }
}
