<?php

namespace App\Http\Requests\Package;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePackageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'agency' => 'required',
            'package_count' => 'required|integer',
            'external_entity' => 'required|string|max:255',
            'internal_person_id' => 'required|exists:internal_people,id',
            'entry_time' => 'required|date',
            'exit_time' => 'required|date',
            'comment' => 'nullable|string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
