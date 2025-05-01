<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AttachAlarmGuardRequest;
use App\Http\Resources\AlarmResource;
use App\Models\Api\Alarm;
use App\Models\Api\Guard;

class AlarmGuardController extends Controller
{
    public function attach(AttachAlarmGuardRequest $request)
    {
        abort_if(!auth()->user()->tokenCan('attach-alarm-guard'), 403, __('Not authorized'));

        $guard = Guard::with('alarms')->findOrFail($request->guard_id);
        $alarm = Alarm::findOrFail($request->alarm_id);

        $guard->alarms()->attach($alarm->id, [
            'date' => now(),
            'is_false_alarm' => $request->is_false_alarm,
            'notes' => $request->notes,
        ]);

        $alarm->refresh();
        $alarm->load(['assignedGuards' => function ($query) use ($guard) {
            $query->where('guard_id', $guard->id);
        }]);

        return new AlarmResource($alarm);
    }
}
