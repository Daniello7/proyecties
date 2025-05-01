<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardReportRequest;
use App\Http\Resources\GuardReportResource;
use App\Models\Api\GuardReport;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GuardReportController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $guardReports = GuardReport::with('assignedGuard')->get();

        return GuardReportResource::collection($guardReports);
    }

    /**
     * @param GuardReportRequest $request
     * @return GuardReportResource
     */
    public function store(GuardReportRequest $request)
    {
        $guardReport = GuardReport::create($request->validated());

        return new GuardReportResource($guardReport);
    }

    /**
     * @urlParam id int required ID of the report. Example: 100
     * @param $id
     * @return GuardReportResource
     */
    public function show($id)
    {
        $guardReport = GuardReport::findOrFail($id);

        return new GuardReportResource($guardReport);
    }

    /**
     * @urlParam id int required ID of the report. Example: 100
     * @param GuardReportRequest $request
     * @param $id
     * @return GuardReportResource
     */
    public function update(GuardReportRequest $request, $id)
    {
        $guardReport = GuardReport::findOrFail($id);
        $guardReport->update($request->all());

        return new GuardReportResource($guardReport);
    }

    /**
     * @urlParam id int required ID of the report. Example: 100
     * @param $id
     * @return GuardReportResource
     */
    public function destroy($id)
    {
        $guardReport = GuardReport::findOrFail($id);
        $guardReport->delete();

        return new GuardReportResource($guardReport);
    }
}
