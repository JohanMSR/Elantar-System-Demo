<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\I013tTiposAgua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['waterTypes', 'creator'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $waterTypes = I013tTiposAgua::all();

        return view('dashboard.expenses.index', compact('expenses', 'waterTypes'));
    }

    public function create()
    {
        $waterTypes = I013tTiposAgua::all();
        return view('dashboard.expenses.create', compact('waterTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'estimated_cost' => 'required|numeric|min:0',
            'water_types' => 'required|array|min:1',
            'water_types.*' => 'exists:i013t_tipos_aguas,co_tipo_agua'
        ]);

        $expense = Expense::create([
            'name' => $request->name,
            'description' => $request->description,
            'estimated_cost' => $request->estimated_cost,
            'created_by' => Auth::id(),
            'is_active' => true
        ]);

        $expense->waterTypes()->attach($request->water_types);

        return redirect()->route('expenses.index')
            ->with('success', 'Gasto creado exitosamente.');
    }

    public function edit(Expense $expense)
    {
        $waterTypes = I013tTiposAgua::all();
        $selectedWaterTypes = $expense->waterTypes->pluck('id')->toArray();
        
        return view('dashboard.expenses.edit', compact('expense', 'waterTypes', 'selectedWaterTypes'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'estimated_cost' => 'required|numeric|min:0',
            'water_types' => 'required|array|min:1',
            'water_types.*' => 'exists:i013t_tipos_aguas,co_tipo_agua'
        ]);

        $expense->update([
            'name' => $request->name,
            'description' => $request->description,
            'estimated_cost' => $request->estimated_cost
        ]);

        $expense->waterTypes()->sync($request->water_types);

        return redirect()->route('expenses.index')
            ->with('success', 'Gasto actualizado exitosamente.');
    }

    public function destroy(Expense $expense)
    {
        $expense->update(['is_active' => false]);
        
        return redirect()->route('expenses.index')
            ->with('success', 'Gasto eliminado exitosamente.');
    }

    public function updateWaterTypes(Request $request, Expense $expense)
    {
        $request->validate([
            'water_types' => 'required|array|min:1',
            'water_types.*' => 'exists:i013t_tipos_aguas,co_tipo_agua'
        ]);

        $expense->waterTypes()->sync($request->water_types);

        return response()->json([
            'success' => true,
            'message' => 'Tipos de agua actualizados exitosamente.'
        ]);
    }

    public function getWaterTypes(Expense $expense)
    {
        $waterTypes = $expense->waterTypes->pluck('id')->toArray();
        
        return response()->json([
            'water_types' => $waterTypes
        ]);
    }
} 