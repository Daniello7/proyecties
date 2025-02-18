<?php

namespace App\Http\Requests;

use App\Models\PersonEntry;
use Illuminate\Foundation\Http\FormRequest;

class EditPersonEntryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'integer|exists:users,id',
            'person_id' => 'required|integer|exists:people,id',
            'internal_person_id' => 'required|integer|exists:internal_people,id',
            'reason' => 'required|string|in:' . join(',', PersonEntry::REASONS),
            'comment_id' => 'string|nullable',
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
