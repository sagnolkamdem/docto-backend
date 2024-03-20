<?php

namespace Modules\Authentication\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Modules\Authentication\Rules\RealEmailValidator;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'email',
                'nullable',
                'string',
                'max:255',
                Rule::unique('users', 'email'),
                new RealEmailValidator,
            ],
            'password' => ['required', Password::default()],
            'phone_number' => [
                'string',
                'nullable',
                'max:255',
                Rule::unique('users', 'phone_number'),
            ],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
