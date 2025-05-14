<?php

namespace App\Http\Requests\PersonEntry;

use App\Models\PersonEntry;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonEntryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'internal_person_id' => 'required|integer|exists:internal_people,id',
            'reason' => 'required|string|in:' . join(',', PersonEntry::REASONS),
            'comment' => 'string|nullable|max:255',
            'arrival_time' => 'date|required',
            'entry_time' => 'date|required',
            'exit_time' => 'date|required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
