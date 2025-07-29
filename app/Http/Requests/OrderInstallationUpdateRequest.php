<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderInstallationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cambia según tu lógica de autorización
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'co_plomero' => [
                'nullable',
                'integer',
                'exists:c002t_usuarios,co_usuario'
            ],
            'co_manager' => [
                'nullable',
                'integer',
                'exists:c002t_usuarios,co_usuario'
            ],
            'co_orden' => [
                'required',
                'integer',
                'exists:c038t_ordenes,co_orden'
            ],
            'co_aplicacion' => [
                'required',
                'integer',
                'exists:c001t_aplicaciones,co_aplicacion'
            ],           
            'fe_registro' => [
                'nullable',
                'date'
            ],
            'fe_instalacion' => [
                'required',
                'date',                
            ],
            'co_status_orden' => [
                'required',
                'integer',
                'exists:i022t_status_ordenes,co_status_orden'
            ],
            // Expenses validation (c046t_expense_by_io)
            'expenses' => 'nullable|array',
            'expenses.*.co_expense' => 'required_with:expenses|integer|exists:i023t_expense,id',
            'expenses.*.bo_expense' => 'required_with:expenses|boolean',
            'expenses.*.nu_amount' => 'required_with:expenses|numeric',

            // Verifications validation (c045t_verification_by_io)
            'verifications' => 'nullable|array',
            'verifications.*.co_verification' => 'required_with:verifications|integer|exists:i027t_verification,id',
            'verifications.*.bo_verification' => 'required_with:verifications|boolean',
            
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'co_plomero.nullable' => 'El plomero es opcional.',
            'co_plomero.integer' => 'El ID del plomero debe ser un número entero.',
            'co_plomero.exists' => 'El plomero seleccionado no existe en el sistema.',
            
            'co_manager.nullable' => 'El manager es opcional.',
            'co_manager.integer' => 'El ID del manager debe ser un número entero.',
            'co_manager.exists' => 'El manager seleccionado no existe en el sistema.',
            
            'co_orden.required' => 'El código de la orden es requerido.',
            'co_orden.integer' => 'El código de la orden debe ser un número entero.',
            'co_orden.exists' => 'La orden seleccionada no existe en el sistema.',
            
            //'fe_registro.nullable' => 'La fecha de registro es opcional.',
            'fe_registro.date' => 'La fecha de registro debe ser una fecha válida.',
            
            'fe_instalacion.required' => 'La fecha de instalación es requerida.',
            'fe_instalacion.date' => 'La fecha de instalación debe ser una fecha válida.',
            
            'co_status_orden.required' => 'El estado de la orden es requerido.',
            'co_status_orden.integer' => 'El estado de la orden debe ser un número entero.',
            'co_status_orden.exists' => 'El estado de la orden seleccionado no existe en el sistema.',

            'expenses.*.co_expense.required_with' => 'El codigo de gasto es requerido.',
            'expenses.*.co_expense.integer' => 'El codigo de gasto debe ser un número entero.',
            'expenses.*.co_expense.exists' => 'El codigo de gasto seleccionado no existe en el sistema.',

            'expenses.*.bo_expense.required_with' => 'El estatus del gasto es requerido.',
            'expenses.*.bo_expense.boolean' => 'El estatus del gasto debe ser un booleano.',

            'expenses.*.nu_amount.required_with' => 'El monto del gasto es requerido.',
            'expenses.*.nu_amount.numeric' => 'El monto del gasto debe ser un número.',

            'verifications.*.co_verification.required_with' => 'El codigo de Verificacion es requerido.',
            'verifications.*.co_verification.integer' => 'El codigo de Verificacion debe ser un número entero.',
            'verifications.*.co_verification.exists' => 'El codigo de Verificacion seleccionado no existe en el sistema.',

            'verifications.*.bo_verification.required_with' => 'El estatus de Verificacion es requerido.',
            'verifications.*.bo_verification.boolean' => 'El estatus de Verificacion debe ser un booleano.',           
            
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'co_aplicacion' => 'aplicación',
            'co_plomero' => 'plomero',
            'co_manager' => 'manager',
            'co_orden' => 'codigo de la orden',
            'fe_instalacion' => 'fecha de instalación',
            'co_status_orden' => 'estado de la orden',            
            'expenses' => 'expenses',
            'expenses.*.co_expense' => 'Codigo de Gasto',
            'expenses.*.bo_expense' => 'Estatus del Gasto',
            'expenses.*.nu_amount' => 'Monto del Gasto',
            'verifications' => 'verifications',
            'verifications.*.co_verification' => 'Codigo de Verificacion',
            'verifications.*.bo_verification' => 'Estatus de Verificacion',
        ];
    }

    /**
     * Configure the validator instance.
     */
    /*
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validaciones adicionales personalizadas
            if ($this->fe_real_instalacion && $this->fe_estimada_instalacion) {
                if ($this->fe_real_instalacion < $this->fe_estimada_instalacion) {
                    $validator->errors()->add('fe_real_instalacion', 'La fecha real de instalación no puede ser anterior a la fecha estimada.');
                }
            }

            // Validar que el plomero y manager no sean la misma persona
            if ($this->co_plomero == $this->co_manager) {
                $validator->errors()->add('co_manager', 'El manager no puede ser el mismo que el plomero.');
            }
        });
    }
    */
    /**
     * Prepare the data for validation.
     */
    /*
    protected function prepareForValidation()
    {
        // Limpiar y formatear datos antes de la validación
        $this->merge([
            'va_costo_total' => $this->va_costo_total ? (float) $this->va_costo_total : null,
            'co_prioridad' => $this->co_prioridad ? (int) $this->co_prioridad : null,
        ]);
    }
    */
}