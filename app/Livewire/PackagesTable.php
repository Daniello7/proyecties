<?php

namespace App\Livewire;

use App\Models\Package;
use App\Traits\HasTableEloquent;
use App\Traits\PackageTableConfig;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class PackagesTable extends Component
{
    use HasTableEloquent, PackageTableConfig;

    public bool $isHomeView = false;

    public function mount(): void
    {
        if ($this->isHomeView) {
            $this->configurePackageHomeView();
        } else {
            $this->configurePackageAllColumns();
        }
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

    public function getPackageHomeView(): Collection
    {
        $query = Package::query()
            ->with($this->relations)
            ->select($this->select)
            ->joinCustodyAndOwner()
            ->whereNull('exit_time');

        $this->applySearchFilter($query);

        $query->orderBy($this->sortColumn, $this->sortDirection);
        if ($this->sortColumn != 'entry_time') {
            $query->orderBy('entry_time', $this->sortDirection);
        }

        return $query->get();
    }

    public function getPackageRows(): Collection|LengthAwarePaginator
    {
        if ($this->isHomeView)
            return $this->getPackageHomeView();

        return $this->getPackageIndexView();
    }

    public function updatePackage($id): void
    {
        $package = Package::findOrFail($id);

        $package->update([
            'entry_time' => now(),
            'receiver_user_id' => auth()->user()->id,
        ]);

        session()->flash('package-status', __('messages.package_updated'));
    }

    public function deletePackage($id): void
    {
        $package = Package::findorFail($id);

        $package->delete();

        session()->flash('package-status', __('messages.package_deleted'));
    }

    public function render()
    {
        return view('livewire.packages-table', ['rows' => $this->getPackageRows()]);
    }
}
