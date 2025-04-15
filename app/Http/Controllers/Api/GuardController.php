<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AssignZoneRequest;
use App\Http\Requests\Api\StoreGuardRequest;
use App\Http\Requests\UpdateGuardRequest;
use App\Models\Guard;
use App\Models\Zone;
use Illuminate\Http\Request;

class GuardController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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

        return response()->json($query->get());
    }

    /**
     * @param StoreGuardRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreGuardRequest $request)
    {
        $validated = $request->validated();

        $guard = Guard::create($validated);

        return response()->json($guard, 201);
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $guard = Guard::find($id);

        if (!$guard) {
            return response()->json([
                'error' => 'Guard not found'
            ], 404);
        }

        $guard = Guard::findOrFail($id);
        return response()->json($guard);
    }

    /**
     * @param UpdateGuardRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateGuardRequest $request, string $id)
    {
        $validated = $request->validated();

        $guard = Guard::findOrFail($id);
        $guard->update($validated);

        return response()->json($guard, 204);
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $guard = Guard::findOrFail($id);

        $guard->delete();

        return response()->json(null, 204);
    }

    /**
     * @param AssignZoneRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignZone(AssignZoneRequest $request)
    {
        $guard = Guard::with('zones')->findOrFail($request->guard_id);
        $zone = Zone::findOrFail($request->zone_id);

        $guard->zones()->syncWithoutDetaching([$zone->id => ['schedule' => $request->schedule]]);

        return response()->json(['message' =>
            "Guard '$guard->name' with id: $guard->id has been assigned the zone '$zone->name' with schedule: $request->schedule"],
            201);
    }
}
