<?php

namespace App\Observers;

use App\Jobs\ForceDeletePackageJob;
use App\Models\Package;

class PackageObserver
{
    public function deleted(Package $package): void
    {
        if ($package->trashed() && !$package->isForceDeleting()) {
            ForceDeletePackageJob::dispatch()->delay(now()->addMonth());
        }
    }
}
