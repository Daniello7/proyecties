<?php

namespace App\Http\Requests\KeyControl;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKeyControlRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key_id' => 'required|integer|exists:key_controls,id',
            'person_id' => 'required|integer|exists:people,id',
            'deliver_user_id' => 'required|integer|exists:users,id',
            'receiver_user_id' => 'required|integer|exists:users,id',
            'comment' => 'string|nullable',
            'exit_time' => 'required|date',
            'entry_time' => 'required|date',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
