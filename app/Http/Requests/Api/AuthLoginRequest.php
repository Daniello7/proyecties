<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam email email required Email. Example: test@mail.com
 * @bodyParam password string required Password. Example: 123
 */
class AuthLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
