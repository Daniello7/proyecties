<?php

namespace App\Http\Requests\PersonEntry;

use App\Models\PersonEntry;
use Illuminate\Foundation\Http\FormRequest;

class StorePersonEntryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'person_id' => 'required|integer|exists:people,id',
            'internal_person_id' => 'required|integer|exists:internal_people,id',
            'reason' => 'required|string|in:' . join(',', PersonEntry::REASONS),
            'comment' => 'string|nullable',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
