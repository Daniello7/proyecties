<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AlarmRequest;
use App\Http\Resources\AlarmResource;
use App\Models\Api\Alarm;

class AlarmController extends Controller
{
    public function index()
    {
        return AlarmResource::collection(Alarm::all());
    }

    public function store(AlarmRequest $request)
    {
        $alarm = Alarm::create($request->all());

        return new AlarmResource($alarm);
    }

    /**
     * @queryParam show_triggers boolean optional Show the Triggers counts and info. Example: 0
     * @urlParam id int required ID of the Alarm. Example: 50
     * @param $id
     * @return AlarmResource
     */
    public function show($id)
    {
        $alarm = Alarm::findOrFail($id);

        return new AlarmResource($alarm);
    }

    /**
     * @urlParam id int required ID of the Alarm. Example: 50
     * @param AlarmRequest $request
     * @param $id
     * @return AlarmResource
     */
    public function update(AlarmRequest $request, $id)
    {
        $alarm = Alarm::findOrFail($id);
        $alarm->update($request->all());

        return new AlarmResource($alarm);
    }

    /**
     * @urlParam id int required ID of the Alarm. Example: 50
     * @param $id
     * @return AlarmResource
     */
    public function destroy($id)
    {
        $alarm = Alarm::findOrFail($id);
        $alarm->delete();

        return new AlarmResource($alarm);
    }
}
