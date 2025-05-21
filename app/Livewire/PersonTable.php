<?php

namespace App\Livewire;

use App\Events\NotifyContactVisitorEvent;
use App\Http\Requests\Person\StorePersonRequest;
use App\Http\Requests\Person\UpdatePersonRequest;
use App\Http\Requests\PersonEntry\StorePersonEntryRequest;
use App\Models\Person;
use App\Models\PersonEntry;
use App\Traits\HasTableEloquent;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class PersonTable extends Component
{
    use WithPagination, HasTableEloquent;

    public ?string $activeModal = null;
    public ?int $id = null;
    public ?Person $person = null;
    public ?PersonEntry $entry = null;

    // Form properties
    public $reason;
    public $person_id;
    public $internal_person_id;
    public $arrival_time;
    public $entry_time;
    public $exit_time;
    public $document_number;
    public $name;
    public $last_name;
    public $company;
    public $comment;

    protected $formRequest;

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
            $this->loadPersonData();
        }

        if ($modal === 'createEntry') {
            $this->person = Person::with(['personEntries' => function ($q) {
                $q->orderBy('exit_time', 'desc')->first();
            }])->find($id);

            $this->entry = $this->person->personEntries->first();

            $this->loadPersonData();
            $this->loadLastEntryData();
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

    private function loadLastEntryData(): void
    {
        if (!$this->entry) return;
        $this->internal_person_id = $this->entry->internal_person_id;
        $this->arrival_time = substr($this->entry->arrival_time, 0, -3);
        $this->entry_time = substr($this->entry->entry_time, 0, -3);
        $this->exit_time = $this->entry->exit_time ? substr($this->entry->exit_time, 0, -3) : null;
        $this->reason = $this->entry->reason;
        $this->comment = null;
    }

    public function storePerson(): void
    {
        $this->formRequest = new StorePersonRequest();

        $validated = $this->validate($this->formRequest->rules());

        Person::create($validated);

        session()->flash('person-status', __('messages.person_created'));

        $this->closeModal();
    }

    public function updatePerson(): void
    {
        $this->formRequest = new UpdatePersonRequest();


        $validated = $this->validate($this->formRequest->rules());

        $this->person->update($validated);

        session()->flash('person-status', __('messages.person_updated'));

        $this->closeModal();
    }

    public function storePersonEntry()
    {
        $this->formRequest = new StorePersonEntryRequest();

        $validated = $this->validate($this->formRequest->rules());
        $validated['user_id'] = auth()->user()->id;
        $validated['arrival_time'] = now();

        if ($this->enter) $validated['entry_time'] = now();

        PersonEntry::create($validated);

        session()->flash('success', __('messages.person-entry_created'));

        if ($this->notify) event(new NotifyContactVisitorEvent($this->entry));

        if ($this->reason == 'Charge' || $this->reason == 'Discharge') {
            $pdfUrl = route('driver-rules', ['person' => $this->person]);
        } elseif ($this->reason == 'Cleaning') {
            $pdfUrl = route('cleaning-rules', ['person' => $this->person]);
        } else {
            $pdfUrl = route('visitor-rules', ['person' => $this->person]);
        }

        $this->dispatch('openRulesPdf', url: $pdfUrl);

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
