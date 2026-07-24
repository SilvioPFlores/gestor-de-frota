<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function index()
    {
        // Busca as viagens com os relacionamentos para evitar N+1 queries
        $trips = Trip::with(['vehicle', 'driver'])->orderBy('departure_time', 'desc')->get();
        
        // Busca veículos e motoristas (você pode adicionar um ->where('status', 'Disponível') se quiser)
        $vehicles = Vehicle::all();
        $drivers = Driver::all();

        return view('trips.index', compact('trips', 'vehicles', 'drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'purpose' => 'required|string|max:255',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'departure_time' => 'required|date',
            'arrival_time' => 'nullable|date|after_or_equal:departure_time',
            'status' => 'required|string',
            'observations' => 'nullable|string'
        ]);

        Trip::create($validated);
        return back()->with('success', 'Viagem cadastrada com sucesso!');
    }

    public function update(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'purpose' => 'required|string|max:255',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'departure_time' => 'required|date',
            'arrival_time' => 'nullable|date|after_or_equal:departure_time',
            'status' => 'required|string',
            'observations' => 'nullable|string'
        ]);

        $trip->update($validated);
        return back()->with('success', 'Viagem atualizada com sucesso!');
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();
        return back()->with('success', 'Viagem cancelada/excluída com sucesso!');
    }
}
