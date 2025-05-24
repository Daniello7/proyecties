<?php

namespace App\Livewire;

use App\Models\InternalPerson;
use App\Traits\HasTableEloquent;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class InternalPersonTable extends Component
{
    use HasTableEloquent;

    public ?string $activeModal = null;
    public ?int $id = null;
    public ?InternalPerson $employer = null;

    // Form properties
    public $email;
    public $phone;
    public $address;
    public $country;
    public $city;
    public $zip_code;
    public $contract_type;

    protected $rules = [
        'email' => 'required|email',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'country' => 'required|string|max:100',
        'city' => 'required|string|max:100',
        'zip_code' => 'required|numeric',
        'contract_type' => 'required|string|max:100',
    ];

    public function mount()
    {
        $this->configureInternalPersonIndexView();
    }

    public function configureInternalPersonIndexView(): void
    {
        $this->columns = ['Nº Employer', 'Name', 'Last Name', 'Actions'];
        $this->select = [
            'internal_people.id',
            'person.name',
            'person.last_name',
        ];
        $this->sortColumn = 'internal_people.id';
        $this->sortDirection = 'asc';
        $this->columnMap = [
            'Nº Employer' => 'internal_people.id',
            'Name' => 'person.name',
            'Last Name' => 'person.last_name',
            'Actions' => null,
        ];
        if (auth()->user()->hasAnyRole('rrhh', 'admin')) {
            array_splice($this->columns, 3, 0, ['Email', 'Phone']);
            array_push($this->select, 'internal_people.email', 'internal_people.phone');
            $this->columnMap['Email'] = 'internal_people.email';
            $this->columnMap['Phone'] = 'internal_people.phone';
        }
    }

    public function getInternalPeople(): Collection
    {
        $query = InternalPerson::query()
            ->select($this->select)
            ->join('people as person', 'person.id', '=', 'internal_people.person_id');

        $this->applySearchFilter($query);

        return $query
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->get();
    }

    public function openModal(string $modal, ?int $id): void
    {
        $this->activeModal = $modal;

        if ($modal === 'editInternalPerson') {
            $this->id = $id;
            $this->employer = InternalPerson::with('person')->findOrFail($id);
            $this->loadInternalPersonData();
        }
    }

    public function closeModal(): void
    {
        $this->resetExceptConfig();
    }

    public function loadInternalPersonData(): void
    {
        $this->email = $this->employer->email;
        $this->phone = $this->employer->phone;
        $this->address = $this->employer->address;
        $this->country = $this->employer->country;
        $this->city = $this->employer->city;
        $this->zip_code = $this->employer->zip_code;
        $this->contract_type = $this->employer->contract_type;
    }

    public function updateInternalPerson(): void
    {
        $validated = $this->validate();

        $this->employer->update($validated);

        $this->closeModal();

        session()->flash('internal-person-status', __('messages.person_updated'));
    }

    public function render()
    {
        return view('livewire.internal-person-table', ['internalPeople' => $this->getInternalPeople()]);
    }
}
