<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Solo usuarios autenticados llegarán aquí por el middleware
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            // Validamos que el email sea único, excepto para el usuario actual
            'email' => ['sometimes', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user()->id)],
            'city' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'shipping_address' => ['nullable', 'string', 'max:255'],
            'billing_address' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            // Validación estricta para la imagen de perfil
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}