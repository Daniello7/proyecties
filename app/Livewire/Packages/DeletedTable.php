<?php

namespace App\Livewire\Packages;

use App\Models\Package;
use App\Traits\HasTableEloquent;
use App\Traits\PackageTableConfig;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class DeletedTable extends Component
{
    use HasTableEloquent, PackageTableConfig;

    public ?string $activeModal = null;
    public ?int $package_id = null;

    public function mount(): void
    {
        $this->configurePackageAllColumns();
        $this->sortColumn = 'deleted_at';
        $this->sortDirection = 'desc';
    }

    public function openModal(string $modal, ?int $id = null): void
    {
        $this->activeModal = $modal;
        $this->package_id = $id;
    }

    public function closeModal(): void
    {
        $this->activeModal = null;
    }

    public function getPackages(): LengthAwarePaginator
    {
        $query = Package::query()
            ->with($this->relations)
            ->select($this->select)
            ->onlyTrashed()
            ->joinCustodyAndOwner(true);

        $this->applySearchFilter($query);

        return $query->orderBy($this->sortColumn, $this->sortDirection)->paginate(50);
    }

    public function restore(int $id): void
    {
        $package = Package::onlyTrashed()->findOrFail($id);
        $package->restore();

        session()->flash('package-status', __('messages.package_restored'));

        $this->activeModal = null;
    }

    public function forceDelete(int $id): void
    {
        $package = Package::onlyTrashed()->findOrFail($id);
        $package->forceDelete();

        session()->flash('package-status', __('messages.package_deleted'));

        $this->activeModal = null;
    }

    public function forceDeleteAll(): void
    {
        Package::onlyTrashed()->forceDelete();

        session()->flash('package-status', __('messages.package_deleted'));

        $this->activeModal = null;
    }


    public function render()
    {
        return view('livewire.packages.deleted-table', ['rows' => $this->getPackages()]);
    }
}
