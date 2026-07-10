<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    // Listagem de veículos
    public function index()
    {
        $vehicles = Vehicle::orderBy('id', 'desc')->get();
        return view('vehicles.index', compact('vehicles'));
    }

    // Processa o cadastro de um novo veículo
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate'       => 'required|string|unique:vehicles,plate|max:10',
            'year'        => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'brand'       => 'required|string|max:255',
            'model'       => 'required|string|max:255',
            'color'       => 'required|string|max:255',
            'fuel'        => 'required|string|max:255',
            'current_km'  => 'required|integer|min:0',
            'status'      => 'required|string|in:Disponível,Em Uso,Manutenção,Inativo',
            'notes'       => 'nullable|string',
        ]);

        Vehicle::create($validated);

        return redirect()->back()->with('success', 'Veículo cadastrado com sucesso!');
    }
    // Atualiza um veículo existente
    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            // A regra abaixo ignora o veículo atual para não dar erro de "Placa já cadastrada"
            'plate'       => 'required|string|max:10|unique:vehicles,plate,' . $vehicle->id,
            'year'        => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'brand'       => 'required|string|max:255',
            'model'       => 'required|string|max:255',
            'color'       => 'required|string|max:255',
            'fuel'        => 'required|string|max:255',
            'current_km'  => 'required|integer|min:0',
            'status'      => 'required|string|in:Disponível,Em Uso,Manutenção,Inativo',
            'notes'       => 'nullable|string',
        ]);

        $vehicle->update($validated);

        return redirect()->back()->with('success', 'Veículo atualizado com sucesso!');
    }

    // Exclui um veículo
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->back()->with('success', 'Veículo removido da frota!');
    }
}