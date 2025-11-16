<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Allow only authenticated admin users
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Force JSON response (no redirects)
     */
    public function expectsJson(): bool
    {
        return true;
    }

    /**
     * Validation rules for creating a new user
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            'email' => ['required', 'email', 'unique:users,email'],

            'password' => ['required', 'string', 'min:6'],

            'role' => ['required', 'in:user,manager,admin'],
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Email already exists.',
            'role.in' => 'Role must be user, manager, or admin.',
        ];
    }
}
