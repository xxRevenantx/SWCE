<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1) Seeders base (catálogos / permisos / etc.)
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            DiaSeeder::class,
            CountriesTableSeeder::class,
            LicenciaturaSeeder::class,
            MesSeeder::class,
            CuatrimestreSeeder::class,
            GeneracionSeeder::class,
        ]);

        // 2) Crear ALUMNOS antes de inscripciones (porque inscripciones depende de alumnos)
        //    Ajusta el count a lo que quieras.
        \App\Models\Alumno::factory()->count(10)->create();

        // 3) Ahora sí, inscripciones (ya existen alumnos/licenciaturas/generaciones/cuatrimestres)
        $this->call([
            InscripcionSeeder::class,
            DatosEscolaresSeeder::class,
            DatosContactoSeeder::class,
        ]);

        // 4) Admin por default al final (como lo traías)
        $email = env('DEFAULT_ADMIN_EMAIL', 'admin@swce.com');
        $password = env('DEFAULT_ADMIN_PASSWORD', 'swce#2026');

        $user = \App\Models\User::updateOrCreate(
            ['email' => $email],
            [
                'username' => 'SWCE',
                'password' => bcrypt($password),
                'email_verified_at' => now(),
            ]
        );

        if (!$user->hasRole('Admin')) {
            $user->assignRole('Admin');
        }
    }
}
