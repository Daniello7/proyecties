<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePackageShippingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'agency' => 'required|string',
            'external_entity' => 'required|string',
            'internal_person_id' => 'required|integer|exists:internal_people,id',
            'package_count' => 'required|integer',
            'comment' => 'nullable|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
