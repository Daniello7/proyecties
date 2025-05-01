<?php

namespace App\Http\Resources;

use App\Models\Api\Alarm;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Alarm */
class AlarmResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'is_active' => $this->is_active,
            'description' => $this->description,
            'zone' => new ZoneResource($this->zone),
            'alarm_triggers_count' => $this->whenLoaded('assignedGuards',
                fn() => $this->assignedGuards->count()),
            'alarm_triggers' =>
                $this->whenLoaded('assignedGuards', function () {
                    return $this->assignedGuards->map(function ($guard) {
                        return [
                            'alarm_trigger_id' => $guard->id,
                            'guard' => new GuardResource($guard),
                            'date' => $guard->pivot->date,
                            'is_false_alarm' => $guard->pivot->is_false_alarm,
                            'notes' => $guard->notes
                        ];
                    });
                }),
        ];
    }
}
