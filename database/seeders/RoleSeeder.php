<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear los roles principales del sistema
        $role1 = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'Profesor']);
        $role3 = Role::create(['name' => 'Estudiante']);

        // Crear el permiso 'admin.dashboard' y asignarlo al rol 'Admin'
        Permission::create(['name' => 'admin.dashboard'])->syncRoles([$role1]);

        // Crear el permiso 'admin.usuarios' y asignarlo al rol 'Admin'
        Permission::create(['name' => 'admin.usuarios'])->syncRoles([$role1]);


    }
}
