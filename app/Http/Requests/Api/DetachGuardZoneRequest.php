<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam guard_id integer required ID of the Guard. Example: 10
 * @bodyParam zone_id integer required ID of the Zone. Example: 10
 */
class DetachGuardZoneRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'guard_id' => 'required|integer|exists:guards,id',
            'zone_id' => 'required|integer|exists:zones,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
