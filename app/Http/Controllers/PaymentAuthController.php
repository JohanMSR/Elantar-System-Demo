<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentAuthController extends Controller
{
    public function create(Request $request)
    {
        // Validate co_aplicacion exists in request
        $co_aplicacion = $request->query('co_aplicacion');
        if (!$co_aplicacion) {
            return redirect()->route('account')->with('error', 'No application ID provided.');
        }

        // Get states from i010t_estados
        $states = DB::table('i010t_estados')
            ->select('co_estado', 'tx_nombre')
            ->orderBy('tx_nombre')
            ->get();
        
        return view('dashboard.forms.payment-auth', compact('co_aplicacion', 'states'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'co_aplicacion' => 'required|integer',
            'nu_monto' => 'required|numeric|min:0',
            'tx_nombre' => 'required|string|max:255',
            'tx_telefono' => 'required|string|max:255',
            'tx_email' => 'required|email|max:255',
            'tx_direccion1' => 'required|string|max:255',
            'tx_direccion2' => 'nullable|string|max:255',
            'tx_ciudad' => 'required|string|max:255',
            'co_estado' => 'required|integer|exists:i010t_estados,co_estado',
            'tx_zip' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Check if a record already exists for this application
            $existing = DB::table('c043t_credicard')
                ->where('co_aplicacion', $request->co_aplicacion)
                ->first();

            if ($existing) {
                // Update existing record
                DB::table('c043t_credicard')
                    ->where('co_aplicacion', $request->co_aplicacion)
                    ->update([
                        'nu_monto' => $request->nu_monto,
                        'tx_nombre' => $request->tx_nombre,
                        'tx_telefono' => $request->tx_telefono,
                        'tx_email' => $request->tx_email,
                        'tx_direccion1' => $request->tx_direccion1,
                        'tx_direccion2' => $request->tx_direccion2,
                        'tx_ciudad' => $request->tx_ciudad,
                        'co_estado' => $request->co_estado,
                        'tx_zip' => $request->tx_zip,
                    ]);
            } else {
                // Insert new record
                DB::table('c043t_credicard')->insert([
                    'co_aplicacion' => $request->co_aplicacion,
                    'nu_monto' => $request->nu_monto,
                    'tx_nombre' => $request->tx_nombre,
                    'tx_telefono' => $request->tx_telefono,
                    'tx_email' => $request->tx_email,
                    'tx_direccion1' => $request->tx_direccion1,
                    'tx_direccion2' => $request->tx_direccion2,
                    'tx_ciudad' => $request->tx_ciudad,
                    'co_estado' => $request->co_estado,
                    'tx_zip' => $request->tx_zip,
                    'fe_registro' => now()
                ]);
            }

            return redirect()
                ->route('account')
                ->with('success_register', 'Payment authorization form submitted successfully.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error submitting payment authorization form. Please try again.')
                ->withInput();
        }
    }
}