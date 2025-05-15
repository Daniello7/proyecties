<?php

namespace App\Livewire\Packages;

use App\Http\Requests\Package\UpdatePackageRequest;
use App\Models\Package;
use App\Traits\HasTableEloquent;
use App\Traits\PackageTableConfig;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class IndexTable extends Component
{
    use HasTableEloquent, PackageTableConfig;

    public ?string $activeModal = null;
    public ?int $package_id;
    public ?Package $package = null;

    public UpdatePackageRequest $formRequest;

    // Form Properties
    public $type;
    public $agency;
    public $external_entity;
    public $internal_person_id;
    public $package_count;
    public $entry_time;
    public $exit_time;
    public $comment;

    public function openModal($modal, int $id = null): void
    {
        $this->activeModal = $modal;
        $this->package_id = $id;

        if ($id) $this->loadPackageData();
    }

    public function closeModal(): void
    {
        $this->resetExceptConfig();
    }

    public function loadPackageData(): void
    {
        $this->package = Package::findOrFail($this->package_id);

        $this->type = $this->package->type;
        $this->agency = $this->package->agency;
        $this->external_entity = $this->package->external_entity;
        $this->internal_person_id = $this->package->internal_person_id;
        $this->package_count = $this->package->package_count;
        $this->entry_time = substr($this->package->entry_time, 0, -3);
        $this->exit_time = substr($this->package->exit_time, 0, -3);
        $this->comment = $this->package->comment;
    }

    public function mount(): void
    {
        $this->configurePackageAllColumns();
    }

    public function getPackageIndexView(): LengthAwarePaginator
    {
        $query = Package::query()
            ->with($this->relations)
            ->select($this->select)
            ->joinCustodyAndOwner(true)
            ->whereNotNull('exit_time');

        $this->applySearchFilter($query);

        return $query->orderBy($this->sortColumn, $this->sortDirection)->paginate(50);
    }

    public function getPackages(): Collection|LengthAwarePaginator
    {
        return $this->getPackageIndexView();
    }

    public function updatePackage($id): void
    {
        $this->formRequest = new UpdatePackageRequest();

        $validated = $this->validate($this->formRequest->rules());

        $package = Package::findorFail($id);

        $package->update($validated);

        session()->flash('package-status', __('messages.package_updated'));

        $this->closeModal();
    }

    public function deletePackage($id): void
    {
        $package = Package::findorFail($id);

        $package->delete();

        session()->flash('package-status', __('messages.package_deleted'));

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.packages.index-table', ['rows' => $this->getPackages()]);
    }
}
