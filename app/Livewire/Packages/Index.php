<?php

namespace App\Livewire\Packages;

use App\Events\NotifyContactPackageEvent;
use App\Http\Requests\Package\StorePackageRequest;
use App\Models\Package;
use Livewire\Component;

class Index extends Component
{
    public bool $openedPackageList = true;
    public bool $openedPackagesDeleted = false;
    public bool $openedCreateReception = false;
    public bool $openedCreateShipping = false;

    protected StorePackageRequest $formRequest;

    // Form Properties
    public $type;
    public $agency;
    public $external_entity;
    public $internal_person_id;
    public $package_count;
    public $comment;
    public $notify = false;

    public function openPackageList(): void
    {
        $this->authorize('viewAny', Package::class);

        $this->closeWindows();
        $this->openedPackageList = true;
    }

    public function openPackagesDeleted(): void
    {
        $this->authorize('cancel', Package::class);

        $this->closeWindows();
        $this->openedPackagesDeleted = true;
    }

    public function openCreateReception(): void
    {
        $this->authorize('create', Package::class);
        $this->closeWindows();
        $this->openedCreateReception = true;
    }

    public function openCreateShipping(): void
    {
        $this->authorize('create', Package::class);
        $this->closeWindows();
        $this->openedCreateShipping = true;
    }

    private function closeWindows(): void
    {
        $this->openedPackageList = false;
        $this->openedPackagesDeleted = false;
        $this->openedCreateReception = false;
        $this->openedCreateShipping = false;
    }

    public function storePackage($type): void
    {
        $this->authorize('create', Package::class);

        $this->type = $type;

        if (!in_array($this->type, ['entry', 'exit'])) abort(404);

        $this->formRequest = new StorePackageRequest();

        $validated = $this->validate($this->formRequest->rules());
        $validated['type'] = $this->type;
        $validated['receiver_user_id'] = auth()->user()->id;
        $validated['entry_time'] = now();

        $package = Package::create($validated);

        if ($this->notify) {
            event(new NotifyContactPackageEvent($package));
        }

        session()->flash('package-status', __('messages.package_stored'));

        $this->reset();
    }

    public function render()
    {
        return view('livewire.packages.index');
    }
}
