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
        $user = auth()->user();
        abort_if(!$user->tokenCan('read-alarms'),
            403, __('Not authorized'));

        return AlarmResource::collection(Alarm::all());
    }

    public function store(AlarmRequest $request)
    {
        $user = auth()->user();
        abort_if(!$user->tokenCan('store-alarms'),
            403, __('Not authorized'));

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
        $user = auth()->user();
        abort_if(!$user->tokenCan('read-alarms'),
            403, __('Not authorized'));

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
        $user = auth()->user();
        abort_if(!$user->tokenCan('update-alarms'),
            403, __('Not authorized'));

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
        $user = auth()->user();
        abort_if(!$user->tokenCan('delete-alarms'),
            403, __('Not authorized'));

        $alarm = Alarm::findOrFail($id);
        $alarm->delete();

        return new AlarmResource($alarm);
    }

}
