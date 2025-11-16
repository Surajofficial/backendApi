<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;

class LoginRequest extends FormRequest
{
    /**
     * Always allow request (no auth needed yet)
     */
    public function shouldReturnJson(): bool
    {
        return true;
    }
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Force JSON always — prevents redirect
     */


    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'captcha_answer' => ['required', 'numeric']
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'This email is not registered in our system.',
            'captcha_answer.required' => 'Please solve the captcha to proceed.',
        ];
    }

    /**
     * Validate captcha after basic rules
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $key = $this->input('captcha_key');
            $answer = (int) $this->input('captcha_answer');

            $expected = Cache::pull('captcha_' . $key);
            // pull = get + delete (ek hi baar use)

            if (is_null($expected)) {
                $validator->errors()->add('captcha_answer', 'Captcha expired or invalid.');
                return;
            }

            if ($answer !== (int) $expected) {
                $validator->errors()->add('captcha_answer', 'Invalid captcha calculation.');
            }
        });
    }
}
