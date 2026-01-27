<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Para que "order" no choque con el admin u otros
        $order = (int) (User::max('order') ?? 0);

        // 10 Estudiantes
        $estudiantes = User::factory()
            ->count(10)
            ->estudiante()
            ->create()
            ->each(function (User $user) use (&$order) {
                $order++;
                $user->update(['order' => $order]);

                if (!$user->hasRole('Estudiante')) {
                    $user->assignRole('Estudiante');
                }
            });

        // 10 Profesores
        $profesores = User::factory()
            ->count(10)
            ->profesor()
            ->create()
            ->each(function (User $user) use (&$order) {
                $order++;
                $user->update(['order' => $order]);

                if (!$user->hasRole('Profesor')) {
                    $user->assignRole('Profesor');
                }
            });
    }
}
