<?php

namespace App\Http\Requests\KeyControl;

use Illuminate\Foundation\Http\FormRequest;

class StoreKeyControlRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key_id' => 'required|integer|exists:keys,id',
            'person_id' => 'required|integer|exists:people,id',
            'comment' => 'string|nullable',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
