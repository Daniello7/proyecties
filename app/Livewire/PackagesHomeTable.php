<?php

namespace App\Livewire;

use App\Models\Package;
use App\Traits\HasTableEloquent;
use App\Traits\PackageTableConfig;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class PackagesHomeTable extends Component
{
    use HasTableEloquent, PackageTableConfig;

    public ?string $activePackageCommentInput = null;
    public string $packageComment = '';

    public function mount(): void
    {
        $this->configurePackageHomeView();
    }

    public function openCommentInput($id): void
    {
        $this->activePackageCommentInput = "package_$id";
    }

    public function closeCommentInput(): void
    {
        $this->activePackageCommentInput = null;
    }

    public function updatePackageComment($id): void
    {
        $package = Package::find($id);

        $package->update(['comment' => $this->packageComment]);

        session()->flash('package-status', __('messages.comment_updated'));

        $this->closeCommentInput();
    }

    public function getPackages(): Collection
    {
        $query = Package::query()
            ->with($this->relations)
            ->select($this->select)
            ->joinCustodyAndOwner()
            ->whereNull('exit_time');

        $this->applySearchFilter($query);

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query->get();
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
        return view('livewire.packages-home-table', ['rows' => $this->getPackages()]);
    }
}
