<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Criar as Permissões
        Permission::create(['name' => 'gerenciar usuarios']);
        Permission::create(['name' => 'gerenciar veiculos']);
        Permission::create(['name' => 'ver relatorios']);

        // 2. Criar os Níveis de Acesso (Roles) e atribuir permissões
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all()); // Admin pode tudo

        $gestorRole = Role::create(['name' => 'Gestor']);
        $gestorRole->givePermissionTo(['gerenciar veiculos', 'ver relatorios']);

        $motoristaRole = Role::create(['name' => 'Motorista']);
        // Motorista inicialmente não tem permissões administrativas

        // 3. Criar um Usuário Admin de Teste
        $adminUser = User::create([
            'name' => 'Silvio Administrador',
            'email' => 'admin@frota.com',
            'password' => Hash::make('12345678'), // Altere depois
        ]);

        // Atribuir o nível de Admin a ele
        $adminUser->assignRole($adminRole);
    }
}
