<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadRequest extends FormRequest
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
        //'phone' => 'required|max:18|regex:/^\+?([0-9]{1,3})?\s?\(?([0-9]{3})\)?[- ]?([0-9]{3})[- ]?([0-9]{4})$/',
        return [
            'nombre' => ['required', 'string', 'min:1', 'max:50'],
            'primera_letra_segundo_nombre' => ['nullable', 'string', 'min:1', 'max:1'],
            'apellido' => ['nullable', 'string', 'min:1', 'max:50'],
            //'email' => ['nullable', 'email:rfc', 'min:2', 'max:50'],
            'email' => ['nullable', 'max:250'],
            'city' => ['nullable', 'string', 'max:200'],
            'state' => ['nullable', 'integer'],
            'zip' => ['nullable','string', 'max:25'],
            //'zip' => ['required','digits_between:3,10'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'direccion2' => ['nullable', 'string', 'max:255'],
            //'telefono' => ['nullable', 'string', 'max:20','regex:/^\+?([0-9]{1,3})?\s?\(?([0-9]{3})\)?[- ]?([0-9]{3})[- ]?([0-9]{4})$/'],
            'telefono' => ['nullable', 'string', 'max:255'],
            'fecha_cita' => ['nullable', 'date'],            
            'hora_cita' => ['nullable', 'regex:/^(0[0-9]|1[0-9]|12):[0-5][0-9] (AM|PM)$/'],
            'select_qbowner' => ['required', 'integer'],
            'select_fuente' => ['nullable', 'integer','exists:i020t_tipo_fuente_cliente,co_fuente'],
            'nota' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
