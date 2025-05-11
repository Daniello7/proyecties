<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActiveEntriesPDF extends FormRequest
{
    public function rules(): array
    {
        return [
            'columns' => 'required|json',
            'entries' => 'required|json',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
