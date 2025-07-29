<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinancieraRequest extends FormRequest
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
            'co_aplicacion' => 'required|integer',
            'co_financiera' => 'required|integer',
            'co_tipo_financiamiento' => 'nullable|integer',
            'tx_rango' => 'nullable|string',            
            'tx_meses' => 'nullable|string',
            'nu_porcentajeap' => 'nullable|numeric',
            'nu_tasa_interes' => 'nullable|numeric',
            'nu_pago_mensual' => 'nullable|numeric'
            
        ];
    }
        
    public function messages()
    {
        return [
            'co_aplicacion.required' => 'El código de la aplicacion es requerido.',
            'co_aplicacion.numeric' => 'El código de la aplicacion debe ser un entero.',
            'co_financiera.required' => 'El código de la financiera es requerido.',
            'co_financiera.numeric' => 'El código de la financiera debe ser un entero.',
            'co_tipo_financiamiento.numeric' => 'El código del tipo de financiamiento debe ser un entero.',                        
            'nu_porcentajeap.numeric' => 'El porcentaje de aprobación debe ser un número.',
            'nu_tasa_interes.numeric' => 'La tasa de interés debe ser un número.',
            'nu_pago_mensual.numeric' => 'El pago mensual debe ser un número.'
        ];
    }
    
}
