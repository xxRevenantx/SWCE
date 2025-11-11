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

        Permission::create(['name' => 'admin.administracion'])->syncRoles([$role1]);
        // Crear el permiso 'admin.usuarios' y asignarlo al rol 'Admin'
        Permission::create(['name' => 'admin.usuarios'])->syncRoles([$role1]);
        // Crear el permiso 'admin.licenciaturas' y asignarlo al rol 'Admin'
        Permission::create(['name' => 'admin.licenciaturas'])->syncRoles([$role1]);

        // Crear el permiso 'admin.cuatrimestres' y asignarlo al rol Admin
        Permission::create(['name' => 'admin.cuatrimestres'])->syncRoles([$role1]);

        // Permisos para 'generaciones' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.generaciones'])->syncRoles([$role1]);

        // Permisos para 'inscripciones' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.inscripciones'])->syncRoles([$role1]);

        // Permisos para 'profesores' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.profesores'])->syncRoles([$role1]);

        // Permisos para 'materias' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.materias'])->syncRoles([$role1]);

        // Permisos para 'horarios' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.horarios'])->syncRoles([$role1]);




        // Crear el permiso 'profesor.dashboard' y asignarlo al rol 'Profesor'
        Permission::create(['name' => 'profesor.dashboard'])->syncRoles([$role2]);





        // Crear el permiso 'estudiante.dashboard' y asignarlo al rol 'Estudiante'
        Permission::create(['name' => 'estudiante.dashboard'])->syncRoles([$role3]);




    }
}
