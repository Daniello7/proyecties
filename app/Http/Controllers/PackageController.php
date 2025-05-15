<?php

namespace App\Http\Controllers;

use App\Events\NotifyContactPackageEvent;
use App\Http\Requests\Package\StorePackageRequest;
use App\Http\Requests\Package\UpdatePackageRequest;
use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        return view('packages.index');
    }
}
