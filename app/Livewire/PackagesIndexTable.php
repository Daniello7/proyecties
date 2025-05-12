<?php

namespace App\Livewire;

use App\Models\Package;
use App\Traits\HasTableEloquent;
use App\Traits\PackageTableConfig;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class PackagesIndexTable extends Component
{
    use HasTableEloquent, PackageTableConfig;

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

    public function deletePackage($id): void
    {
        $package = Package::findorFail($id);

        $package->delete();

        session()->flash('package-status', __('messages.package_deleted'));
    }

    public function render()
    {
        return view('livewire.packages-index-table', ['rows' => $this->getPackages()]);
    }
}
