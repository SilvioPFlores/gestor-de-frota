<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // Lista os usuários e os níveis disponíveis
    public function index()
    {
        // O with('roles') evita o problema de N+1 consultas no banco
        $users = User::with('roles')->get();
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    // Atualiza o nível de acesso do usuário
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        // Evita que o Admin logado tire o seu próprio acesso de Admin
        if ($user->id === auth()->id() && $request->role !== 'Admin') {
            return redirect()->back()->with('error', 'Você não pode remover seu próprio nível de Administrador!');
        }

        // syncRoles remove o nível antigo e coloca o novo
        $user->syncRoles($request->role);

        return redirect()->back()->with('success', "Nível de acesso de {$user->name} atualizado!");
    }
}