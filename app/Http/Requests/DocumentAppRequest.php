<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentAppRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ajusta esto según tus necesidades de autorización
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        
        // Define un array con los nombres de los campos de archivos.
        $fileFields = [
            'tx_url_img_compago1',
            'tx_url_img_compago2',
            'tx_url_img_compago3',
            'tx_url_img_checknull',
            'tx_url_img_compropiedad',
            'tx_url_img_declaraimpuesto',
        ];
        
        // Crea las reglas de validación dinámicamente.
        $rules = [];
        foreach ($fileFields as $field) {
            $rules[$field] = [
                'nullable', // Permite que el campo sea nulo.
                'file',     // Valida que sea un archivo.
                'max:3072', // Tamaño máximo de 3MB en KB.
                'mimes:jpeg,png,jpg,gif,webp,pdf', // Tipos MIME permitidos.
            ];
        }
        // Agrega la regla para co_aplicacion
        $rules['co_aplicacion'] = 'required|integer';

        return $rules;
    }



     /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
             'max' => 'El tamaño máximo del archivo es de 3MB.',
             'mimes' => 'El archivo debe ser una imagen (jpeg, png, jpg, gif) o un documento (pdf, doc, docx, xls, xlsx).',

        ];
    }
}