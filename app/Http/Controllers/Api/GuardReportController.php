<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\GuardReport;
use Illuminate\Http\Request;

class GuardReportController extends Controller
{
    public function index()
    {
        return GuardReport::all();
    }

    public function store(Request $request)
    {
        return GuardReport::create($request->all());
    }

    public function show($id)
    {
        return GuardReport::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $guardReport = GuardReport::findOrFail($id);
        $guardReport->update($request->all());

        return $guardReport;
    }

    public function destroy($id)
    {
        $guardReport = GuardReport::findOrFail($id);
        $guardReport->delete();

        return $guardReport;
    }
}
