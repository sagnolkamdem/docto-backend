<?php

namespace Modules\Core\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ChangeCountryStatusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'is_enabled' => 'boolean',
            'is_active' => 'boolean',
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
