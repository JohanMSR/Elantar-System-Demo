<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFinancialStatusRequest extends FormRequest
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
            //'metodo_pago' => 'required|string',
            'co_aplicacion' => 'required|integer',
            'status_financial' => 'required|integer',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //'metodo_pago.required' => 'El tipo de financiación es requerido.',
            //'metodo_pago.string' => 'El tipo de financiación debe ser una cadena de texto.',
            'co_aplicacion.required' => 'El código de aplicación es requerido.',
            'co_aplicacion.integer' => 'El código de aplicación debe ser un número entero.',
            'status_financial.required' => 'El estado financiero es requerido.',
            'status_financial.integer' => 'El estado financiero debe ser un número entero.',
        ];
    }
}
