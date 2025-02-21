<?php

namespace App\Http\Controllers;

use App\Http\Requests\KeyControl\StoreKeyControlRequest;
use App\Models\KeyControl;
use Illuminate\Http\Request;

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

        return to_route('control-access');
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
