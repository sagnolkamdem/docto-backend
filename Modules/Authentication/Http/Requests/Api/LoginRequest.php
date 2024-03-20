<?php

namespace Modules\Authentication\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Authentication\Rules\RealEmailValidator;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['email','nullable', new RealEmailValidator],
            'phone_number' => 'nullable',
            'password' => 'required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
