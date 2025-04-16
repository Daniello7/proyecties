<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AssignZoneRequest;
use App\Http\Requests\Api\StoreGuardRequest;
use App\Http\Requests\Api\UpdateGuardRequest;
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
     * @bodyParam name string required The name of the guard. Example: Daniel
     * @bodyParam dni string required The Document number of the guard. Example: 12345678A
     * @param StoreGuardRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreGuardRequest $request)
    {
        $guard = Guard::create($request->validated());

        return response()->json($guard, 201);
    }

    /**
     * @urlParam id int required The ID of the guard. Example: 1
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $guard = Guard::find($id);

        if (!$guard) {
            return response()->json([
                'error' => __('Guard not found')
            ], 404);
        }

        return response()->json($guard, 201);
    }

    /**
     * @urlParam id int required The ID of the guard. Example: 1
     * @bodyParam name string required The name of the guard. Example: Daniel
     * @bodyParam dni string required The Document number of the guard. Example: 12345678A
     *
     * @param UpdateGuardRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateGuardRequest $request, string $id)
    {
        $guard = Guard::find($id);

        if (!$guard) {
            return response()->json([
                'error' => __('Guard not found')
            ], 404);
        }

        $guard->update($request->validated());

        return response()->json($guard);
    }

    /**
     * @urlParam id int required The ID of the guard. Example: 1
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $guard = Guard::find($id);

        if (!$guard) {
            return response()->json([
                'error' => __('Guard not found')
            ], 404);
        }

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

        return response()->json(['message' => __('Zone assigned successfully.')]);
    }
}
