<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'dni' => 'required|string|max:255|unique:guards',
        ]);

        $guard = Guard::create($request->validated());

        return response()->json($guard, 201);
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $guard = Guard::findOrFail($id);
        return response()->json($guard);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'dni' => 'required|string|max:255|unique:guards,dni,' . $id,
        ]);

        $guard = Guard::findOrFail($id);
        $guard->update($request->validated());

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
     * @param Request $request
     * @param $guardId
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignZone(Request $request, $guardId)
    {
        $guard = Guard::findOrFail($guardId);
        $zone = Zone::findOrFail($request->zone_id);

        $guard->zones()->attach($zone->id, ['schedule' => $request->schedule]);

        return response()->json(['message' => 'Zone assigned with schedule'], 201);
    }
}
