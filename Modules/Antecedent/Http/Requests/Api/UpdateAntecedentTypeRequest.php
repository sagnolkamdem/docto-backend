<?php

namespace Modules\Antecedent\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAntecedentTypeRequest extends FormRequest
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
        return auth()->check() && auth()->user()->hasRole('root|admin|manager');
    }
}
