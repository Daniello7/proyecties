<?php

namespace App\Livewire;

use App\Models\Package;
use App\Traits\HasTableEloquent;
use App\Traits\PackageTableConfig;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class PackagesDeletedTable extends Component
{
    use HasTableEloquent, PackageTableConfig;

    public function mount(): void
    {
        $this->configurePackageAllColumns();
        $this->sortColumn = 'deleted_at';
        $this->sortDirection = 'desc';
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

    public function forceDelete(Package $package): void
    {
        $package->forceDelete();
    }

    public function restore(Package $package): void
    {
        $package->restore();
    }

    public function render()
    {
        return view('livewire.packages-deleted-table', ['rows' => $this->getPackages()]);
    }
}
