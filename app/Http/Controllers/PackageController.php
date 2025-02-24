<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePackageReceptionRequest;
use App\Http\Requests\StorePackageShippingRequest;
use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        return view('packages.index');
    }

    public function create()
    {
        return view('packages.create');
    }

    public function createExit()
    {
        return view('packages.createExit');
    }

    public function store(StorePackageReceptionRequest $request)
    {
        $data = $request->validated();
        $data['receiver_user_id'] = auth()->user()->id;
        $data['entry_time'] = now();

        Package::create($data);

        return to_route('control-access');
    }

    public function storeExit(StorePackageShippingRequest $request)
    {
        $data = $request->validated();
        $data['type'] = 'exit';
        $data['receiver_user_id'] = auth()->user()->id;
        $data['entry_time'] = now();

        Package::create($data);

        return to_route('control-access');
    }

}
