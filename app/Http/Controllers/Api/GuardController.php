<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guard;
use App\Models\Zone;
use Illuminate\Http\Request;

class GuardController extends Controller
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Guard::with('zones')->get();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $guard = Guard::create($request->only(['name', 'dni']));
        return response()->json($guard, 201);
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
