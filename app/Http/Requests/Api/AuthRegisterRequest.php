<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam name string required Your username. Example: Test User
 * @bodyParam email email required Email. Example: test@mail.com
 * @bodyParam password string required Password. Example: 123
 * @bodyParam password_confirmation password required Password. Example: 123
 */
class AuthRegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:3|confirmed',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
