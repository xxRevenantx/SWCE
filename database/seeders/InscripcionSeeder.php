<?php

namespace Database\Seeders;

use App\Models\Alumno;
use App\Models\Cuatrimestre;
use App\Models\Generacion;
use App\Models\Licenciatura;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InscripcionSeeder extends Seeder
{
    public function run(): void
    {
        // Catálogos
        $alumnos = Alumno::query()->pluck('id')->values();
        $licenciaturas = Licenciatura::query()->pluck('id')->values();
        $generaciones = Generacion::query()->pluck('id')->values();
        $cuatrimestres = Cuatrimestre::query()->pluck('id')->values();

        // Si falta algún catálogo, no hacemos nada para evitar errores
        if ($alumnos->isEmpty() || $licenciaturas->isEmpty() || $generaciones->isEmpty() || $cuatrimestres->isEmpty()) {
            return;
        }

        // ✅ Objetivo de inscripciones (máximo = número de alumnos, porque NO se repite alumno_id)
        $objetivo = 10;
        $objetivo = min($objetivo, $alumnos->count());

        // ✅ Tomo alumnos en orden aleatorio y solo uso los primeros N (garantiza alumno_id único en esta corrida)
        $alumnosElegidos = $alumnos->shuffle()->take($objetivo)->values();

        $rows = [];
        $now = now();

        foreach ($alumnosElegidos as $alumnoId) {

            // ✅ Si ya existe una inscripción para ese alumno en BD, lo saltamos
            // (esto aplica tu regla: alumno_id no debe repetirse en inscripciones)
            $yaInscrito = DB::table('inscripciones')
                ->where('alumno_id', $alumnoId)
                ->exists();

            if ($yaInscrito) {
                continue;
            }

            $rows[] = [
                'alumno_id' => $alumnoId,
                'licenciatura_id' => $licenciaturas->random(),
                'generacion_id' => $generaciones->random(),
                'cuatrimestre_id' => $cuatrimestres->random(),
                'status' => (bool) (random_int(1, 10) <= 9), // 90% true
                'fecha_inscripcion' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Inserción masiva
        if (!empty($rows)) {
            DB::table('inscripciones')->insert($rows);
        }
    }
}
