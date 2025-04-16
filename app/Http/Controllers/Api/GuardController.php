<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AssignZoneRequest;
use App\Http\Requests\Api\StoreGuardRequest;
use App\Http\Requests\Api\UpdateGuardRequest;
use App\Http\Resources\GuardResource;
use App\Models\Guard;
use App\Models\Zone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GuardController extends Controller
{
    /**
     * @queryParam name string Name of the Guard. Example: Daniel
     * @queryParam dni string DNI of the Guard. Example: 12345678A
     * @queryParam with_zones boolean Show zones of the Guard. Example: false
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = Guard::query();

        if ($request->has('name')) {
            $query->name($request->name);
        }

        if ($request->has('dni')) {
            $query->dni($request->dni);
        }

        return GuardResource::collection($query->get());
    }

    /**
     * @bodyParam name string required The name of the guard. Example: Daniel
     * @bodyParam dni string required The Document number of the guard. Example: 12345678A
     * @param StoreGuardRequest $request
     * @return JsonResponse
     */
    public function store(StoreGuardRequest $request)
    {
        $guard = Guard::create($request->validated());

        return response()->json($guard, 201);
    }

    /**
     * @urlParam id int required The ID of the guard. Example: 1
     * @queryParam with_zones boolean Show zones of the Guard. Example: false
     * @param int $id
     * @return GuardResource
     */
    public function show(int $id)
    {
        $guard = Guard::findOrFail($id);

        return new GuardResource($guard);
    }

    /**
     * @urlParam id int required The ID of the guard. Example: 1
     * @bodyParam name string required The name of the guard. Example: Daniel
     * @bodyParam dni string required The Document number of the guard. Example: 12345678A
     * @param UpdateGuardRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateGuardRequest $request, int $id)
    {
        $guard = Guard::findOrFail($id);

        $guard->update($request->validated());

        return response()->json($guard);
    }

    /**
     * @urlParam id int required The ID of the guard. Example: 1
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $guard = Guard::findOrFail($id);

        $guard->delete();

        return response()->json(null, 204);
    }

    /**
     * @param AssignZoneRequest $request
     * @return JsonResponse
     */
    public function assignZone(AssignZoneRequest $request)
    {
        $guard = Guard::with('zones')->findOrFail($request->guard_id);
        $zone = Zone::findOrFail($request->zone_id);

        $guard->zones()->syncWithoutDetaching([$zone->id => ['schedule' => $request->schedule]]);

        return response()->json(['message' => __('Zone assigned successfully.')]);
    }
}
