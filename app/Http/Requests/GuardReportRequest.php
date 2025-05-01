<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam guard_id int required ID of the Guard. Example: 1
 * @bodyParam zone_id int required ID of the Zone. Example: 1
 * @bodyParam entry_time date required Guard entrance time to work. Example: 2025/06/01 7:00
 * @bodyParam exit_time date required Guard exit time from work. Example: 2025/06/01 19:00
 * @bodyParam incident string optional Work incidents. Example: ""
 */
class GuardReportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'guard_id' => 'required|int|exists:guards,id',
            'zone_id' => 'required|int|exists:zones,id',
            'entry_time' => 'required|date',
            'exit_time' => 'required|date',
            'incident' => 'nullable|string'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
