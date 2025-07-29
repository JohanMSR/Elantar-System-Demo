<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function store(Request $request)
    {
        // Lógica para almacenar la propuesta
        return redirect()->back()->with('success', 'Propuesta guardada exitosamente');
    }
}
