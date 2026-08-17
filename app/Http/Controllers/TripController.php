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
        $user = auth()->user();

        $query = Trip::with(['user', 'vehicle', 'driver'])
            ->orderBy('departure_time', 'desc');

        if ($user->hasRole('Solicitante')) {
            $query->where('user_id', $user->id);
        }

        $trips = $query->get();

        $vehicles = Vehicle::all();
        $drivers = Driver::all();

        return view('trips.index', compact('trips', 'vehicles', 'drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purpose' => 'required|string|max:255',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'departure_time' => 'required|date',
            'arrival_time' => 'nullable|date|after_or_equal:departure_time',
            'observations' => 'nullable|string',
        ]);

        Trip::create([
            'user_id' => auth()->id(),
            'purpose' => $validated['purpose'],
            'origin' => $validated['origin'],
            'destination' => $validated['destination'],
            'departure_time' => $validated['departure_time'],
            'arrival_time' => $validated['arrival_time'] ?? null,
            'observations' => $validated['observations'] ?? null,
            'status' => 'Solicitada',
        ]);

        return back()->with(
            'success',
            'Viagem solicitada com sucesso!'
        );
    }

    /**
     * Atualiza os dados da solicitação.
     */
    public function update(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'purpose' => 'required|string|max:255',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'departure_time' => 'required|date',
            'arrival_time' => 'nullable|date|after_or_equal:departure_time',
            'observations' => 'nullable|string',
        ]);

        $trip->update($validated);

        $mensagem = 'Viagem atualizada com sucesso!';
        
        if ($request->ajax()) {
            session()->flash('success', $mensagem);
            return response()->json(['success' => true]);
        }

        return back()->with(
            'success',
            $mensagem
        );
    }

    /**
     * Altera o veículo da viagem.
     */
    public function updateVehicle(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);

        $trip->update($validated);

        $mensagem = 'Veículo da viagem atualizado com sucesso!';
        
        if ($request->ajax()) {
            session()->flash('success', $mensagem);
            return response()->json(['success' => true]);
        }

        return back()->with(
            'success',
            $mensagem
        );
    }

    /**
     * Altera o motorista da viagem.
     */
    public function updateDriver(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $trip->update($validated);

        $mensagem = 'Motorista da viagem atualizado com sucesso!';
        
        if ($request->ajax()) {
            session()->flash('success', $mensagem);
            return response()->json(['success' => true]);
        }

        return back()->with(
            'success',
            $mensagem
        );
    }

    /**
     * Altera o status da viagem.
     */
    public function updateStatus(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Solicitada,Agendada,Em andamento,Concluida,Cancelada',
        ]);

        $trip->update([
            'status' => $validated['status'],
        ]);

        $mensagem = 'Status da viagem atualizado com sucesso!';
        
        if ($request->ajax()) {
            session()->flash('success', $mensagem);
            return response()->json(['success' => true]);
        }

        return back()->with(
            'success',
            $mensagem
        );
    }

    /**
     * Cancela a viagem.
     */
    public function cancel(Trip $trip)
    {
        $trip->update([
            'status' => 'Cancelada',
            'observations' => 'Viagem cancelada pelo usuário',
        ]);

        return back()->with(
            'success',
            'Viagem cancelada com sucesso!'
        );
    }
}
