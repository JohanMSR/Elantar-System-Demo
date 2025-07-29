<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CodigoApplicationRequest extends FormRequest
{
    

    public function rules()
    {
        return [
            'co_application' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'co_application.required' => 'El código de aplicación es obligatorio.',
            'co_application.integer' => 'El código de aplicación debe ser un número entero.',
        ];
    }
}
