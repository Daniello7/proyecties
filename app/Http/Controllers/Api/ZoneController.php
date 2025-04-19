<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ZoneRequest;
use App\Http\Resources\ZoneResource;
use App\Models\Api\Zone;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ZoneController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $user = auth()->user();

        abort_if(!$user->tokenCan('read-zones')
            && !$user->tokenCan('read-own-zones'),
            403, __('Not authorized'));

        $zones = Zone::ownZones();

        return ZoneResource::collection($zones);
    }

    /**
     * @param ZoneRequest $request
     * @return ZoneResource
     */
    public function store(ZoneRequest $request)
    {
        abort_if(!auth()->user()->tokenCan('store-zones'), 403, __('Not authorized'));

        $zone = Zone::create($request->validated());

        return new ZoneResource($zone);
    }

    /**
     * @urlParam id int required ID of the Zone. Example: 1
     * @param int $id
     * @return ZoneResource
     */
    public function show(int $id)
    {
        abort_if(!auth()->user()->tokenCan('read-zones'), 403, __('Not authorized'));

        return new ZoneResource(Zone::findOrFail($id));
    }

    /**
     * @urlParam id int required ID of the Zone. Example: 15
     * @param ZoneRequest $request
     * @param int $id
     * @return ZoneResource
     */
    public function update(ZoneRequest $request, int $id)
    {
        abort_if(!auth()->user()->tokenCan('update-zones'), 403, __('Not authorized'));

        $zone = Zone::findOrFail($id);
        $zone->update($request->validated());

        return new ZoneResource($zone);
    }

    /**
     * @urlParam id int required ID of the zone. Example: 15
     * @param $id
     * @return ZoneResource
     */
    public function destroy($id)
    {
        abort_if(!auth()->user()->tokenCan('delete-zones'), 403, __('Not authorized'));

        $zone = Zone::findOrFail($id);
        $zone->delete();

        return new ZoneResource($zone);
    }
}
