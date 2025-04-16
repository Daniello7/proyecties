<?php

namespace App\Http\Resources;

use App\Models\Guard;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Guard
 */
class GuardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'dni' => $this->dni,
            'name' => $this->name,
            'zones' => ZoneResource::collection($this->whenLoaded('zones')),
        ];
    }
}
