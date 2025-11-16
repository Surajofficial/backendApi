<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Allow only authenticated admins
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Force JSON response format (avoid redirect)
     */
    public function expectsJson(): bool
    {
        return true;
    }

    /**
     * Validation rules for updating user
     */
    public function rules(): array
    {
        $id = $this->route('id')
            ?? $this->route('user')
            ?? $this->id
            ?? null;

        return [
            'name' => ['required', 'string', 'max:255'],

            // Email: must remain unique except current user
            'email' => ['required', 'email', 'unique:users,email,' . $id],

            // Optional password allowed, only validate if sent
            'password' => ['nullable', 'string', 'min:6'],

            // Role must be within allowed set
            'role' => ['required', 'in:user,manager,admin'],
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already taken.',
            'role.in' => 'Invalid role value.',
        ];
    }
}
