<?php

namespace Modules\Practician\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidatePracticianRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->hasRole('root|admin|manager|secretary_employee');
    }
}
