<?php

namespace App\Http\Controllers;

use App\Events\NotifyContactPackageEvent;
use App\Http\Requests\Package\StorePackageRequest;
use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        return view('packages.index');
    }

    public function create($type = 'entry')
    {
        if (!in_array($type, ['entry', 'exit'])) abort(404);

        return view('packages.create' . ucfirst($type), compact('type'));
    }

    public function store(StorePackageRequest $request, $type = 'entry')
    {
        if (!in_array($type, ['entry', 'exit'])) abort(404);

        $data = $request->validated();
        $data['type'] = $type;
        $data['receiver_user_id'] = auth()->user()->id;
        $data['entry_time'] = now();

        $package = Package::create($data);

        if (isset($request['notify'])) {
            event(new NotifyContactPackageEvent($package));
        }


        return to_route('control-access');
    }
}
