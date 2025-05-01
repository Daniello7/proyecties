<?php

namespace App\Http\Resources;

use App\Models\Api\GuardReport;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin GuardReport */
class GuardReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'zone' => [
                'id' => $this->zone->id,
                'name' => $this->zone->name,
                'location' => $this->zone->location,
            ],
            'guard' => [
                'id' => $this->guard_id,
                'dni' => $this->assignedGuard->dni,
                'name' => $this->assignedGuard->name
            ],
            'entry_time' => $this->entry_time,
            'exit_time' => $this->exit_time,
            'incident' => $this->incident,
        ];
    }
}
