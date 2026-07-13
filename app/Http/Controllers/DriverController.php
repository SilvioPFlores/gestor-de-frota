<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::latest()->get();
        return view('drivers.index', compact('drivers'));
    }

    public function store(Request $request)
    {
        // Valida usando a função centralizada
        $validated = $request->validate($this->getValidationRules());
        $validated['is_active'] = $request->has('is_active');

        Driver::create($validated);
        return redirect()->back()->with('success', 'Motorista cadastrado com sucesso!');
    }

    public function update(Request $request, Driver $driver)
    {
        // Valida passando o ID atual para ignorar a regra de "unique" dele mesmo
        $validated = $request->validate($this->getValidationRules($driver->id));
        $validated['is_active'] = $request->has('is_active');

        $driver->update($validated);
        return redirect()->back()->with('success', 'Cadastro atualizado com sucesso!');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect()->back()->with('success', 'Motorista removido do sistema!');
    }

    /**
     * Centraliza as regras de validação para evitar código duplicado (Princípio DRY)
     */
    private function getValidationRules($driverId = null): array
    {
        return [
            'name'           => 'required|string|max:255',
            'cpf'            => ['required', 'string', 'max:14', 'unique:drivers,cpf' . ($driverId ? ",$driverId" : ''), $this->cpfAlgorithm()],
            'phone'          => 'nullable|string|max:20',
            'cnh'            => ['required', 'string', 'max:20', 'unique:drivers,cnh' . ($driverId ? ",$driverId" : ''), $this->cnhAlgorithm()],
            'cnh_category'   => 'required|string|max:5',
            'cnh_expiration' => 'required|date',
            'email'          => 'nullable|email|max:255',
            'is_active'      => 'boolean',
        ];
    }

    private function cpfAlgorithm(): \Closure
    {
        return function ($attribute, $value, $fail) {
            $cpf = preg_replace('/[^0-9]/', '', $value);
            if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
                return $fail('O CPF informado é matematicamente inválido.');
            }
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return $fail('O CPF informado é matematicamente inválido.');
                }
            }
        };
    }

    private function cnhAlgorithm(): \Closure
    {
        return function ($attribute, $value, $fail) {
            $cnh = preg_replace('/[^0-9]/', '', $value);
            if (strlen($cnh) != 11 || preg_match('/(\d)\1{10}/', $cnh)) {
                return $fail('A CNH informada é matematicamente inválida.');
            }
            $sum1 = 0;
            for ($i = 0, $j = 9; $i < 9; $i++, $j--) {
                $sum1 += (int)$cnh[$i] * $j;
            }
            $dv1 = $sum1 % 11;
            $dsc = 0;
            if ($dv1 > 9) {
                $dv1 = 0;
                $dsc = 2;
            }
            $sum2 = 0;
            for ($i = 0, $j = 1; $i < 9; $i++, $j++) {
                $sum2 += (int)$cnh[$i] * $j;
            }
            $dv2 = $sum2 % 11;
            if ($dv2 > 9) {
                $dv2 = 0;
            } else {
                $dv2 = $dv2 - $dsc;
                if ($dv2 < 0) $dv2 += 11;
            }
            if ((int)$cnh[9] != $dv1 || (int)$cnh[10] != $dv2) {
                return $fail('A CNH informada é matematicamente inválida.');
            }
        };
    }
}