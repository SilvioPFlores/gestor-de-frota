<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Permissões
        |--------------------------------------------------------------------------
        */

        $permissions = [

            // Usuários
            'gerenciar usuarios',

            // Veículos
            'gerenciar veiculos',

            // Relatórios
            'ver relatorios',

            // Viagens
            'viagens.visualizar',
            'viagens.criar',
            'viagens.editar',
            'viagens.editar_dados',
            'viagens.cancelar',
            'viagens.alterar_veiculo',
            'viagens.alterar_motorista',
            'viagens.alterar_status',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $adminRole = Role::firstOrCreate([
            'name' => 'Admin',
        ]);

        $gestorRole = Role::firstOrCreate([
            'name' => 'Gestor',
        ]);

        $motoristaRole = Role::firstOrCreate([
            'name' => 'Motorista',
        ]);

        $solicitanteRole = Role::firstOrCreate([
            'name' => 'Solicitante',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        |
        | Admin possui todas as permissões.
        |
        */

        $adminRole->syncPermissions(
            Permission::all()
        );

        /*
        |--------------------------------------------------------------------------
        | Gestor
        |--------------------------------------------------------------------------
        |
        | Gestor possui todas as funcionalidades, exceto
        | alteração do nível dos usuários.
        |
        */

        $gestorRole->syncPermissions([
            'gerenciar veiculos',
            'ver relatorios',

            'viagens.visualizar',
            'viagens.criar',
            'viagens.editar',
            'viagens.editar_dados',
            'viagens.cancelar',
            'viagens.alterar_veiculo',
            'viagens.alterar_motorista',
            'viagens.alterar_status',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Motorista
        |--------------------------------------------------------------------------
        */

        $motoristaRole->syncPermissions([
            'viagens.visualizar',
            'viagens.alterar_veiculo',
            'viagens.alterar_motorista',
            'viagens.alterar_status',
            'viagens.cancelar',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Solicitante
        |--------------------------------------------------------------------------
        */

        $solicitanteRole->syncPermissions([
            'viagens.visualizar',
            'viagens.criar',
            'viagens.editar',
            'viagens.editar_dados',
            'viagens.cancelar',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Usuário Admin de teste
        |--------------------------------------------------------------------------
        */

        $adminUser = User::firstOrCreate(
            [
                'email' => 'admin@frota.com',
            ],
            [
                'name' => 'Silvio Administrador',
                'password' => Hash::make('1244567890'),
                'email_verified_at' => now(),
            ]
        );

        // Garante que o usuário de teste tenha somente a Role Admin.
        $adminUser->syncRoles($adminRole);
    }
}
