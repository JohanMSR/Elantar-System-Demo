<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\I018tFinanciera;
use App\Models\I019tTipoFinanciamiento;

class FinancieraController extends Controller
{
    public function getFinanciera(Request $request)
    {
        $validatedData = $request->validate([
            'co_financiera' => 'required|integer',
        ], [
            'co_financiera.required' => 'El código de la financiera es requerido.',
            'co_financiera.integer' => 'El código de la financiera debe ser un valor entero.',
        ]);

        $codigo = $validatedData['co_financiera'];
        $financiera = I018tFinanciera::with('i019t_tipo_financiamientos')
        ->find($codigo);

        if (!$financiera) {
            return response()->json([
                'error' => 'Financiera no encontrada'
            ], 404);
        }

        return response()->json(['success' => true, 'data' => $financiera], 200);
    }    
}
