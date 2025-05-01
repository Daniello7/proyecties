<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Alarm;
use Illuminate\Http\Request;

class AlarmController extends Controller
{
    public function index()
    {
        return Alarm::all();
    }

    public function store(Request $request)
    {
        return Alarm::create($request->all());
    }

    public function show($id)
    {
        return Alarm::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $alarm = Alarm::findOrFail($id);
        $alarm->update($request->all());

        return $alarm;
    }

    public function destroy($id)
    {
        $alarm = Alarm::findOrFail($id);
        $alarm->delete();

        return $alarm;
    }
}
