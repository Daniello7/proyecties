<?php

namespace App\Http\Requests\Person;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'document_number' => 'required|string',
            'name' => 'required|string',
            'last_name' => 'required|string',
            'company' => 'required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
