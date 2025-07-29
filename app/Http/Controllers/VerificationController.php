<?php

namespace App\Http\Controllers;

use App\Models\Verification;
use App\Models\I013tTiposAgua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function index()
    {
        $verifications = Verification::with(['waterTypes', 'creator'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $waterTypes = I013tTiposAgua::all();

        return view('dashboard.verifications.index', compact('verifications', 'waterTypes'));
    }

    public function create()
    {
        $waterTypes = I013tTiposAgua::all();
        return view('dashboard.verifications.create', compact('waterTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'water_types' => 'required|array|min:1',
            'water_types.*' => 'exists:i013t_tipos_aguas,co_tipo_agua'
        ]);

        $verification = Verification::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => Auth::id(),
            'is_active' => true
        ]);

        $verification->waterTypes()->attach($request->water_types);

        return redirect()->route('verifications.index')
            ->with('success', 'Verificación creada exitosamente.');
    }

    public function edit(Verification $verification)
    {
        $waterTypes = I013tTiposAgua::all();
        $selectedWaterTypes = $verification->waterTypes->pluck('id')->toArray();
        
        return view('dashboard.verifications.edit', compact('verification', 'waterTypes', 'selectedWaterTypes'));
    }

    public function update(Request $request, Verification $verification)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'water_types' => 'required|array|min:1',
            'water_types.*' => 'exists:i013t_tipos_aguas,co_tipo_agua'
        ]);

        $verification->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        $verification->waterTypes()->sync($request->water_types);

        return redirect()->route('verifications.index')
            ->with('success', 'Verificación actualizada exitosamente.');
    }

    public function destroy(Verification $verification)
    {
        $verification->update(['is_active' => false]);
        
        return redirect()->route('verifications.index')
            ->with('success', 'Verificación eliminada exitosamente.');
    }

    public function updateWaterTypes(Request $request, Verification $verification)
    {
        $request->validate([
            'water_types' => 'required|array|min:1',
            'water_types.*' => 'exists:i013t_tipos_aguas,co_tipo_agua'
        ]);

        $verification->waterTypes()->sync($request->water_types);

        return response()->json([
            'success' => true,
            'message' => 'Tipos de agua actualizados exitosamente.'
        ]);
    }

    public function getWaterTypes(Verification $verification)
    {
        $waterTypes = $verification->waterTypes->pluck('id')->toArray();
        
        return response()->json([
            'water_types' => $waterTypes
        ]);
    }
} 