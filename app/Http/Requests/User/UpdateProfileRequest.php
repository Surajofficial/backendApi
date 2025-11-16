<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Always allow request (authentication handled by middleware)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Force API-style JSON validation response
     */
    public function expectsJson(): bool
    {
        return true;
    }

    /**
     * Validation rules for updating user profile
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            // Email shouldn't change here — but if allowed:
            // 'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->id())],

            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Your name cannot be empty.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }
}
