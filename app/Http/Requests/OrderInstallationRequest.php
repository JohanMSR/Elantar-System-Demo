<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderInstallationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // O tu lógica de autorización
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [];

        // Verificar si ambos campos de fecha están presentes o ninguno
        $tieneFechaInicio = $this->has('fechaInicio');
        $tieneFechaFinal = $this->has('fechaFin');

        // Si uno está presente pero el otro no, agregar reglas que siempre fallen
        if ($tieneFechaInicio && !$tieneFechaFinal) {
            $rules['fechaFin'] = 'required';
        }

        if (!$tieneFechaInicio && $tieneFechaFinal) {
            $rules['fechaInicio'] = 'required';
        }

        // Si ambos están presentes, validar que sean fechas válidas
        if ($tieneFechaInicio && $tieneFechaFinal) {
            $rules['fechaInicio'] = 'required|date';
            $rules['fechaFin'] = 'required|date|after_or_equal:fechaInicio';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'fechaInicio.required' => 'El campo fecha inicio es requerido.',
            'fechaInicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fechaFin.required' => 'El campo fecha final es requerido.',
            'fechaFin.date' => 'La fecha final debe ser una fecha válida.',
            'fechaFin.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha de inicio.',
        ];
    }
}