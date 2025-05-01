<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam zone_id int required ID of the Zone where is the Alarm. Example: 1
 * @bodyParam type string required Alarm type. Example: intrusion
 * @bodyParam is_active boolean required Indicates if the Alarm is active. Example: 1
 * @bodyParam description string optional Description of the Alarm. Example: ""
 */
class AlarmRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'zone_id' => 'required|int|exists:zones,id',
            'type' => 'required|string|max:100',
            'is_active' => 'required|boolean',
            'description' => 'nullable|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
