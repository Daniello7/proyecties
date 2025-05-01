<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AttachGuardZoneRequest;
use App\Http\Requests\Api\DetachGuardZoneRequest;
use App\Http\Resources\GuardResource;
use App\Models\Api\Guard;
use App\Models\Api\Zone;

class GuardZoneController extends Controller
{
    /**
     * @param AttachGuardZoneRequest $request
     * @return GuardResource
     */
    public function attach(AttachGuardZoneRequest $request)
    {
        abort_if(!auth()->user()->tokenCan('attach-guard-zone'), 403, __('Not authorized'));

        $guard = Guard::with('zones')->findOrFail($request->guard_id);
        $zone = Zone::findOrFail($request->zone_id);

        $guard->zones()->syncWithoutDetaching([$zone->id => ['schedule' => $request->schedule]]);

        $guard->load(['zones' => function ($query) use ($zone) {
            $query->where('zone_id', $zone->id);
        }]);

        return new GuardResource($guard);
    }

    public function detach(DetachGuardZoneRequest $request)
    {
        abort_if(!auth()->user()->tokenCan('detach-guard-zone'), 403, __('Not authorized'));

        $zone = Zone::findOrFail($request->zone_id);

        $guard = Guard::with(['zones' => function ($query) use ($zone) {
            $query->where('zone_id', $zone->id);
        }])->findOrFail($request->guard_id);

        if (!$guard->zones->contains($zone)) abort(400, __('Zone is not assigned'));

        $guard->zones()->detach($zone);

        return new GuardResource($guard);
    }
}
