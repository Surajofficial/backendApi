<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // public endpoint
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'captcha_answer' => ['required', 'integer'],
        ];
    }

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
