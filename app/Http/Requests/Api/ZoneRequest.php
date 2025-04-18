<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam name string required Name of the Zone. Example: Zone Name
 * @bodyParam location string required Location of the Zone. Example: C/: Torrealta, S/N
 */
class ZoneRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
