<?php

namespace App\Http\Requests\Person;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'document_number' => 'required|string|max:20',
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'company' => 'required|string|max:100',
            'comment_id' => 'nullable|string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
