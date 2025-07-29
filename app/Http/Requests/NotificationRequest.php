<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'co_usuario' => 'nullable|integer',
		    'co_tiponoti' => 'nullable|integer',
		    'bo_visto' => 'nullable|boolean',
		    'fe_registro' => 'nullable|datetime',
		    'co_aplicacion' => 'nullable|integer',
            'tx_info_general' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            //'co_aplicacion.required' => 'El codigo de la aplicación es requerido.', 
            'co_aplicacion.integer' => 'El codigo de la aplicación debe ser un número entero.',
            'tx_info_general.required' => 'El texto es requerido.',   
            'tx_info_general.string' => 'El texto debe ser una cadena de caracteres.',
            'co_tiponoti.integer' => 'El codigo de la notificación debe ser un número entero.',
        ];
    }
}
