<?php

namespace App\Http\Requests;

use App\Models\PersonEntry;
use Illuminate\Foundation\Http\FormRequest;

class PersonEntryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'person_id' => 'required|integer|exists:persons,id',
            'internal_person_id' => 'required|integer|exists:persons,id',
            'reason' => 'required|string|in:' . join(',', PersonEntry::REASONS),
            'comment_id' => 'required|integer|exists:comments,id',
            'arrival_time' => 'required|datetime',
            'entry_time' => 'datetime',
            'exit_time' => 'datetime',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
