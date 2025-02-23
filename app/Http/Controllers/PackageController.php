<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePackageReceptionRequest;
use App\Models\Package;
use Illuminate\Http\Request;

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

    public function storeExit()
    {

    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
