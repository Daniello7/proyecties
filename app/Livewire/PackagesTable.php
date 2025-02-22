<?php

namespace App\Livewire;

use App\Models\Package;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class PackagesTable extends Component
{
    public array $columns;
    public array $select;
    public array $columnMap;
    public array $relations;
    public string $sortColumn;
    public string $sortDirection;
    public string $search = '';
    public bool $isHomeView = false;

    public function mount(): void
    {
        if ($this->isHomeView) {
            $this->configurePackageHomeView();
        } else {
            $this->configurePackageIndexView();
        }
    }

    public function configurePackageIndexView(): void
    {
        $this->columns = ['Type', 'Agency', 'Sender', 'Destination', 'Entry', 'Receiver', 'Exit', 'Deliver', 'Package Count', 'Retired By', 'Comment', 'Actions'];
        $this->select = ['packages.*'];
        $this->columnMap = [
            'Type' => 'type',
            'Agency' => 'agency',
            'Receiver' => 'receiver.name',
            'Deliver' => 'deliver.name',
            'Entry' => 'entry_time',
            'Exit' => 'exit_time',
            'Package Count' => 'package_count',
            'Retired By' => 'retired_by',
            'Sender' => null,
            'Destination' => null,
            'Comment' => null,
            'Actions' => null
        ];
        $this->relations = [
            'internalPerson:id,person_id',
            'internalPerson.person:id,name,last_name',
            'receiver:id,name',
            'deliver:id,name',
        ];
        $this->sortColumn = 'exit_time';
        $this->sortDirection = 'asc';
    }

    public function configurePackageHomeView(): void
    {
//        $this->columns = ['Person', 'Key', 'Comment', 'Actions'];
//        $this->select = [
//            'packages.id',
//            'packages.person_id',
//            'packages.key_id',
//            'packages.comment',
//        ];
//        $this->columnMap = [
//            'Key' => 'key.name',
//            'Person' => 'person.name',
//            'Comment' => null,
//            'Actions' => null
//        ];
//        $this->relations = [
//            'key',
//            'person:id,name,last_name',
//        ];
//        $this->sortColumn = 'packages.created_at';
//        $this->sortDirection = 'asc';
    }

    public function getPackageIndexView(): LengthAwarePaginator // Últimos registros
    {
        $query = Package::query()
            ->with($this->relations)
            ->select($this->select)
            ->join('internal_people as internalPerson', 'internalPerson.id', '=', 'packages.internal_person_id')
            ->join('people as internalPersonPerson', 'internalPersonPerson.id', '=', 'internalPerson.person_id')
            ->join('users as receiver', 'receiver.id', '=', 'packages.receiver_user_id')
            ->join('users as deliver', 'deliver.id', '=', 'packages.deliver_user_id');


        $this->applySearchFilter($query);

        return $query->orderBy($this->sortColumn, $this->sortDirection)
            ->whereNotNull('exit_time')->paginate(50);
    }

    public function getPackageHomeView(): Collection // Paquetes en portería
    {
        $query = Package::query()
            ->with($this->relations)
            ->select($this->select)
            ->whereNull('exit_time');

        $this->applySearchFilter($query);

        return $query->orderBy($this->sortColumn, $this->sortDirection)->get();
    }

    public function getPackageRows(): Collection|LengthAwarePaginator
    {
        if ($this->isHomeView)
            return $this->getPackageHomeView();

        return $this->getPackageIndexView();
    }

    public function applySearchFilter($query): void
    {
        if (!$this->search) return;

        $query->where(function ($q) {
            foreach ($this->columnMap as $column) {
                if ($column) {
                    $q->orWhere($column, 'LIKE', "%{$this->search}%");
                }
            }
        });
    }

    public function sortBy($column): void
    {
        if (!$this->columnMap[$column]) return;

        $column = $this->columnMap[$column];

        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function updatePackage($id): void
    {
        $keyControl = Package::findOrFail($id);

        $keyControl->update([
            'entry_time' => now(),
            'receiver_user_id' => auth()->user()->id,
        ]);

        session()->flash('key-status', __('messages.key-control_updated'));
    }

    public function deletePackage($id): void
    {
        $keyControl = Package::findorFail($id);

        $keyControl->delete();

        session()->flash('key-status', __('messages.key-control_deleted'));
    }

    public function render()
    {
        return view('livewire.packages-table', ['rows' => $this->getPackageRows()]);
    }
}
