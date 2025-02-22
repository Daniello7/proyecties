<?php

namespace App\Http\Controllers;

use App\Http\Requests\KeyControl\StoreKeyControlRequest;
use App\Http\Requests\KeyControl\UpdateKeyControlRequest;
use App\Models\KeyControl;

class KeyControlController extends Controller
{
    public function index()
    {
        return view('key-control.index');
    }

    public function create()
    {
        return view('key-control.create');
    }

    public function store(StoreKeyControlRequest $request)
    {
        $data = $request->validated();
        $data['deliver_user_id'] = auth()->user()->id;
        $data['exit_time'] = now();

        KeyControl::create($data);

        return to_route('control-access')->with('status', __('messages.key-control_created'));
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $keyControl = KeyControl::with(['key', 'deliver', 'receiver', 'person'])->findOrFail($id);

        return view('key-control.edit', compact('keyControl'));
    }

    public function update(UpdateKeyControlRequest $request, $id)
    {
        $keyControl = KeyControl::with(['key', 'person', 'deliver', 'receiver'])->findOrFail($id);
        $keyControl->update($request->validated());

        return to_route('control-access')->with('status', __('messages.key-control_updated'));
    }

    public function destroy($id)
    {
    }
}
