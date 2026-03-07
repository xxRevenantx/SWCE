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
        $role1 = Role::firstOrCreate(['name' => 'Admin']);
        $role2 = Role::firstOrCreate(['name' => 'Profesor']);
        $role3 = Role::firstOrCreate(['name' => 'Estudiante']);

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

        // Permisos para 'asignacion_generaciones' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.asignacion_generaciones'])->syncRoles([$role1]);

        // Permisos para 'inscripciones' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.inscripciones'])->syncRoles([$role1]);

        // Permisos para 'MATRICULA' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.matricula'])->syncRoles([$role1]);

        // Permisos para 'matricula.editar_alumno' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.matricula.editar_alumno'])->syncRoles([$role1]);

        // Permisos para 'profesores' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.profesores'])->syncRoles([$role1]);

        // Permisos para 'materias' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.materias'])->syncRoles([$role1]);

        // Permisos para 'asignacion_materias' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.asignacion_materias'])->syncRoles([$role1]);

        // Permisos para 'horarios' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.horarios'])->syncRoles([$role1]);

        // Permisos para 'calificaciones' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.calificaciones'])->syncRoles([$role1]);


        // Permisos para 'pdf.expediente_alumno' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.pdf.expediente_alumno'])->syncRoles([$role1]);

        // Permisos para 'pdf.credencial_profesor' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.pdf.credencial_profesor'])->syncRoles([$role1]);

        // Permisos para 'pdf.lista_matricula' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.pdf.lista_matricula'])->syncRoles([$role1]);

        // Permisos para 'pdf.boleta_calificacion' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.pdf.boleta_calificacion'])->syncRoles([$role1]);

        // Permisos para 'pdf.horario' y asignación al rol 'Admin'
        Permission::create(['name' => 'admin.pdf.horario'])->syncRoles([$role1]);


        // Crear el permiso 'profesor.dashboard' y asignarlo al rol 'Profesor'
        Permission::create(['name' => 'profesor.dashboard'])->syncRoles([$role2]);

        // Crear el permiso 'admin.calificaciones' y asignarlo al rol 'Admin'
        Permission::create(['name' => 'admin.pdf.calificaciones'])->syncRoles([$role1]);




        // Crear el permiso 'estudiante.dashboard' y asignarlo al rol 'Estudiante'
        Permission::create(['name' => 'estudiante.dashboard'])->syncRoles([$role3]);
        Permission::create(['name' => 'estudiante.perfil'])->syncRoles([$role3]);
    }
}
