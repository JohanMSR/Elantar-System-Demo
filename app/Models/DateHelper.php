<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class DateHelper extends Model
{
    use HasFactory;

    /**
     * Valida una fecha en formato mm/dd/aaaa.
     *
     * @param string $fecha La fecha a validar.
     * @return bool True si la fecha es válida, false si no.
     */
    public static function validarFecha($fecha)
    {
        // Define la regla de validación personalizada
        Validator::extend('fecha_formato', function ($attribute, $value, $parameters, $validator) {
            // Expresión regular para validar el formato mm/dd/aaaa
            $regex = '/^(0[1-9]|1[0-2])\/(0[1-9]|[12]\d|3[01])\/\d{4}$/';
            return preg_match($regex, $value);
        });

        // Crea un validador para la fecha
        $validator = Validator::make(['fecha' => $fecha], [
            'fecha' => 'required|fecha_formato',
        ]);

        // Devuelve true si la fecha es válida, false si no
        return $validator->passes();
    }
}
