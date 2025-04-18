<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam guard_id integer required ID of the Guard. Example: 10
 * @bodyParam zone_id integer required ID of the Zone. Example: 10
 * @bodyParam schedule string required Schedule format 24h. Example: 20:00 - 10:00
 */
class AttachZoneRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'guard_id' => 'required|integer|exists:guards,id',
            'zone_id' => 'required|integer|exists:zones,id',
            'schedule' => ['required', 'string', 'max:100', 'regex:/^(0?\d|1\d|2[0-3]):[0-5]\d - (0?\d|1\d|2[0-3]):[0-5]\d$/'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
