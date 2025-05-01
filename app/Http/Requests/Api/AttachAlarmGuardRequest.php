<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam alarm_id int required ID of the Alarm. Example: 1
 * @bodyParam guard_id int required ID of the Guard. Example: 1
 * @bodyParam is_false_alarm boolean required Indicates if alarm is fake. Example: 1
 * @bodyParam notes string Notes of the triggered alarm. Example: ""
 */
class AttachAlarmGuardRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'alarm_id' => 'required|int|exists:alarms,id',
            'guard_id' => 'required|int|exists:guards,id',
            'is_false_alarm' => 'required|boolean',
            'notes' => 'nullable|string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
