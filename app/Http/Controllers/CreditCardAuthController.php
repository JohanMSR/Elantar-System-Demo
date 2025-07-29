<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreditCardAuthController extends Controller
{
    public function store(Request $request)
    {
        // Lógica para almacenar la autorización de tarjeta de crédito
        return redirect()->back()->with('success', 'Autorización de tarjeta guardada exitosamente');
    }
}
