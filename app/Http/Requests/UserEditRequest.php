<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UserEditRequest extends FormRequest
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
            'tx_url_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'tx_primer_nombre' => 'required|string|max:255',
            'tx_segundo_nombre' => 'nullable|string|max:255',
            'tx_primer_apellido' => 'required|string|max:255',
            'tx_segundo_apellido' => 'nullable|string|max:255',
            'tx_telefono' => 'required|string|max:20',
            'tx_email' => 'required|email|max:255',
            'tx_password' => 'nullable|string|min:6',
            'tx_direccion1' => 'required|string|max:255',
            'tx_direccion2' => 'nullable|string|max:255',
            'tx_zip' => 'nullable|string|max:64',
            'co_tipo_usuario' => 'required|exists:c013t_tipo_usuarios,co_tipo_usuario',
            'co_usuario_padre' => [
                'required_unless:co_tipo_usuario,2',
                'nullable',
                'exists:c002t_usuarios,co_usuario'
            ],            
            'co_usuario_reclutador' => [
                'required_unless:co_tipo_usuario,2',
                'nullable',
                'exists:c002t_usuarios,co_usuario'
            ],            
            'co_estatus_usuario' => 'required|exists:i014t_estatus_usuarios,co_estatus_usuario',            
            'co_office_manager' => [
                'required_unless:co_tipo_usuario,2',
                'nullable',
                'exists:c002t_usuarios,co_usuario'
            ],            
            'co_sponsor' => [
                'required_unless:co_tipo_usuario,2',
                'nullable',
                'exists:c002t_usuarios,co_usuario'
            ],            
            'Office_City_ID' => [
                'required_unless:co_tipo_usuario,2',
                'exists:i008t_oficinas,co_oficina'
            ],
            'co_rol' => [
                'required_unless:co_tipo_usuario,2',
                'exists:i007t_roles,co_rol'
            ],
            'tx_url_paquete' => 'nullable|file|mimes:pdf|max:10240',
            'tx_url_appempl' => 'nullable|file|mimes:pdf|max:10240',
            'tx_url_drive' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,pdf|max:10240',
            'tx_url_formaw9' => 'nullable|file|mimes:pdf|max:10240',
            'fe_nac' => 'required|date',
            'co_idioma' => 'required|exists:i012t_idiomas,co_idioma',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'required_if' => 'El campo :attribute es obligatorio cuando :other es :value.',
            'required_unless' => 'El campo :attribute es obligatorio a menos que :other sea :value.',
            'email' => 'El campo :attribute debe ser una dirección de correo válida.',
            'max' => 'El campo :attribute no debe ser mayor a :max caracteres.',
            'min' => 'El campo :attribute debe tener al menos :min caracteres.',
            'exists' => 'El :attribute seleccionado no es válido.',
            'tx_primer_nombre.required' => 'El primer nombre es obligatorio.',
            'tx_primer_apellido.required' => 'El primer apellido es obligatorio.',
            'tx_telefono.required' => 'El teléfono es obligatorio.',
            'tx_email.required' => 'El correo electrónico es obligatorio.',
            'tx_email.email' => 'El formato del correo electrónico no es válido.',
            'tx_password.required' => 'La contraseña es obligatoria.',
            'tx_password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'tx_direccion1.required' => 'La dirección es obligatoria.',
            'tx_zip.required' => 'El código ZIP es obligatorio.',
            'co_usuario_padre.required_unless' => 'El usuario padre es obligatorio',
            'co_usuario_reclutador.required_unless' => 'El reclutador es obligatorio',
            'co_estatus_usuario.required' => 'El estatus del usuario es obligatorio.',
            'co_tipo_usuario.required' => 'El tipo de usuario es obligatorio.',
            'co_office_manager.required_unless' => 'El gerente de oficina es obligatorio.',
            'co_sponsor.required_unless' => 'El patrocinador es obligatorio.',
            'Office_City_ID.required' => 'La ciudad de oficina es obligatoria.',
            'co_rol.required_unless' => 'El rol es obligatorio a menos que el tipo de usuario sea 2.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'image.max' => 'La imagen no debe pesar más de 10MB.',
            'tx_url_photo.image' => 'La foto de perfil debe ser tipo imagen.',
            'tx_url_photo.mimes' => 'La foto de perfil debe ser de tipo: jpeg, png, jpg, gif.',
            'tx_url_photo.max' => 'La foto de perfil no debe pesar más de 10MB.',
            'tx_url_paquete.file' => 'El paquete debe ser un archivo PDF.',
            'tx_url_paquete.mimes' => 'El paquete debe ser un archivo PDF.',
            'tx_url_paquete.max' => 'El paquete no debe pesar más de 10MB.',
            'tx_url_appempl.file' => 'El archivo de empleado debe ser un archivo PDF.',
            'tx_url_appempl.mimes' => 'El archivo de empleado debe ser un archivo PDF.',
            'tx_url_appempl.max' => 'El archivo de empleado no debe pesar más de 10MB.',
            'tx_url_drive.file' => 'La Licencia de conducir debe ser un archivo valido.',
            'tx_url_drive.mimes' => 'La Licencia de conducir debe ser un archivo jpeg, png, jpg, gif o PDF.',
            'tx_url_drive.max' => 'La Licencia de conducir no debe pesar más de 10MB.',
            'tx_url_formaw9.file' => 'El Formulario W-9 debe ser un archivo PDF.',
            'tx_url_formaw9.mimes' => 'El Formulario W-9 debe ser un archivo PDF.',
            'tx_url_formaw9.max' => 'El Formulario W-9 no debe pesar más de 10MB.',
            'fe_nac.required' => 'La fecha de nacimiento es obligatoria.',
            'fe_nac.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'co_idioma.required' => 'El idioma es obligatorio.',
            'co_idioma.exists' => 'El idioma seleccionado no es válido.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'tx_primer_nombre' => 'primer nombre',
            'tx_segundo_nombre' => 'segundo nombre',
            'tx_primer_apellido' => 'primer apellido',
            'tx_segundo_apellido' => 'segundo apellido',
            'tx_telefono' => 'teléfono',
            'tx_email' => 'correo electrónico',
            'tx_password' => 'contraseña',
            'tx_direccion1' => 'dirección',
            'tx_direccion2' => 'dirección secundaria',
            'tx_zip' => 'código ZIP',
            'co_usuario_padre' => 'usuario padre',
            'co_usuario_reclutador' => 'reclutador',
            'co_estatus_usuario' => 'estatus del usuario',
            'co_tipo_usuario' => 'tipo de usuario',
            'co_office_manager' => 'gerente de oficina',
            'co_sponsor' => 'patrocinador',
            'Office_City_ID' => 'ciudad de oficina',
            'co_rol' => 'rol',
            'tx_url_photo' => 'foto de perfil',
            'tx_url_paquete' => 'paquete',
            'tx_url_appempl' => 'archivo de empleado',
            'tx_url_drive' => 'licencia de conducir',
            'tx_url_formaw9' => 'formulario W-9',
            'fe_nac' => 'fecha de nacimiento',
            'co_idioma' => 'idioma',
        ];
    }
} 