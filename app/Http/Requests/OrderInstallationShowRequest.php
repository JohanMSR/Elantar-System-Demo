<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderInstallationShowRequest extends FormRequest
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
        return [
            'co_orden' => 'required|integer',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'co_orden.required' => 'El código de la orden es obligatorio.',
            'co_orden.integer' => 'El código de la orden debe ser un número entero.',
        ];
    }
} 