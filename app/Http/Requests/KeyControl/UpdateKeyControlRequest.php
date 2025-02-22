<?php

namespace App\Http\Requests\KeyControl;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKeyControlRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'person_id' => 'required|integer|exists:people,id',
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
