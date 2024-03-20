<?php

namespace Modules\Authentication\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Authentication\Rules\RealEmailValidator;

class ForgetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', new RealEmailValidator,'exists:practicians,email'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
