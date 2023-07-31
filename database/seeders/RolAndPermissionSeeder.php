<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userAdmin = User::create([
            'email' => 'admin@gmail.com',
            'password' => bcrypt('1234'),
        ]);

        $adminRole = Role::create(['name' => 'Admin']);
        $socioRole = Role::create(['name' => 'Socio']);

        $userAdmin->assignRole('Admin');

        // CLIENTES
        Permission::create(['name' => 'clientes.agregar'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'clientes.actualizar'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'clientes.eliminar'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'clientes.buscar'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'clientes.retornarclientes'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'clientes.retornarclientesbasico'])->syncRoles([$adminRole]);

        // ABOGADOS
        Permission::create(['name' => 'abogados.agregar'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'abogados.actualizar'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'abogados.eliminar'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'abogados.buscar'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'abogados.retornarabogados'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'abogados.retornarabogadosbasico'])->syncRoles([$adminRole]);

        // TIPO TRAMITES
        Permission::create(['name' => 'tipotramite.agregar'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'tipotramite.actualizar'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'tipotramite.eliminar'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'tipotramite.retornartipotramite'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'tipotramite.retornartipopretenciones'])->syncRoles([$adminRole]);

        // TRAMITES
        Permission::create(['name' => 'tramites.agregar'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'tramites.editar'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'tramites.listar'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'tramites.buscar'])->syncRoles([$adminRole]);

        // JUZGADOS
        Permission::create(['name' => 'juzgados.retornarjuzgados'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'juzgados.actualizar'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'juzgados.agregar'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'juzgados.pretencionbuscar'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'juzgados.detallebuscar'])->syncRoles([$adminRole]);

        // TIPOS DE PROCESOS
        Permission::create(['name' => 'tiposProcesos.store'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'tiposProcesos.update'])->syncRoles([$adminRole]);

        // CITAS
        Permission::create(['name' => 'citas.store'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'citas.update'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'citas.filtrarCitasAbogado'])->syncRoles([$adminRole]);

        // PRETENCIONES
        Permission::create(['name' => 'pretensiones.update'])->syncRoles([$adminRole]);
    }
}
