<?php

namespace App\Console\Commands;

use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ForceDeleteOldPackagesCommand extends Command
{
    protected $signature = 'packages:clear-old';

    protected $description = 'Definitely delete packages after 30 days';

    public function handle(): void
    {
        $date = Carbon::now()->subMonth();

        $packages = Package::onlyTrashed()
            ->where('deleted_at', '<=', $date)
            ->get();

        $count = $packages->count();

        foreach ($packages as $package) {
            $package->forceDelete();
        }

        $this->info("Se eliminaron $count paquetes de la papelera.");
    }
}
