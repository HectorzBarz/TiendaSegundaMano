<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // En el registro inicial, los demás campos pueden dejarse vacíos o rellenarse
            'city' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'shipping_address' => ['nullable', 'string', 'max:255'],
            'billing_address' => ['nullable', 'string', 'max:255'],
        ];
    }
}