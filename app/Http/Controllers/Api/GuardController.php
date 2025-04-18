<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AssignZoneRequest;
use App\Http\Requests\Api\GuardRequest;
use App\Http\Resources\GuardResource;
use App\Models\Guard;
use App\Models\Zone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GuardController extends Controller
{
    /**
     * @queryParam name string Name of the Guard. Example: a
     * @queryParam dni string DNI of the Guard. Example: 3
     * @queryParam with_zones boolean Show zones of the Guard. Example: false
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        abort_if(!$user->tokenCan('read-guards') && !$user->tokenCan('read-own-guard'),
            403, __('Not authorized'));

        $query = Guard::ownGuard();

        if ($request->has('name')) {
            $query->name($request->name);
        }

        if ($request->has('dni')) {
            $query->dni($request->dni);
        }

        return GuardResource::collection($query->get());
    }

    /**
     * @param GuardRequest $request
     * @return GuardResource
     */
    public function store(GuardRequest $request)
    {
        abort_if(!auth()->user()->tokenCan('store-guards'), 403, __('Not authorized'));

        $guard = Guard::create($request->validated());

        return new GuardResource($guard);
    }

    /**
     * @urlParam id int required The ID of the guard. Example: 1
     * @queryParam with_zones boolean Show zones of the Guard. Example: false
     * @param int $id
     * @return GuardResource
     */
    public function show(int $id)
    {
        $user = auth()->user();

        if ($user->tokenCan('read-guards')) {
            $guard = Guard::findOrFail($id);
        } elseif ($user->tokenCan('read-own-guard')) {
            $guard = Guard::ownGuard()->firstOrFail();
        } else {
            abort(403, __('Not authorized'));
        }

        return new GuardResource($guard);
    }

    /**
     * @urlParam id int required The ID of the guard. Example: 1
     * @param GuardRequest $request
     * @param int $id
     * @return GuardResource
     */
    public function update(GuardRequest $request, int $id)
    {
        abort_if(!auth()->user()->tokenCan('update-guards'), 403, __('Not authorized'));

        $guard = Guard::findOrFail($id);

        $guard->update($request->validated());

        return new GuardResource($guard);
    }

    /**
     * @urlParam id int required The ID of the guard. Example: 1
     * @param int $id
     * @return GuardResource
     */
    public function destroy(int $id)
    {
        abort_if(!auth()->user()->tokenCan('delete-guards'), 403, __('Not authorized'));

        $guard = Guard::findOrFail($id);

        $guard->delete();

        return new GuardResource($guard);
    }

    /**
     * @param AssignZoneRequest $request
     * @return GuardResource
     */
    public function assignZone(AssignZoneRequest $request)
    {
        abort_if(!auth()->user()->tokenCan('store-guard-zone'), 403, __('Not authorized'));

        $guard = Guard::with('zones')->findOrFail($request->guard_id);
        $zone = Zone::findOrFail($request->zone_id);

        $guard->zones()->syncWithoutDetaching([$zone->id => ['schedule' => $request->schedule]]);

        $guard->load(['zones' => function ($query) use ($zone) {
            $query->where('zone_id', $zone->id);
        }]);

        return new GuardResource($guard);
    }
}
