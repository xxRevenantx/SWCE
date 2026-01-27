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
        $alumnos = Alumno::query()->pluck('id')->values();
        $licenciaturas = Licenciatura::query()->pluck('id')->values();
        $generaciones = Generacion::query()->pluck('id')->values();
        $cuatrimestres = Cuatrimestre::query()->pluck('id')->values();

        // Si falta algún catálogo, no hacemos nada para evitar errores
        if ($alumnos->isEmpty() || $licenciaturas->isEmpty() || $generaciones->isEmpty() || $cuatrimestres->isEmpty()) {
            return;
        }

        // ✅ Cantidad de inscripciones a crear (ajusta a tu gusto)
        $objetivo = 200;

        // ✅ Máximo posible de combinaciones únicas
        $maxPosible = $alumnos->count() * $licenciaturas->count() * $generaciones->count() * $cuatrimestres->count();
        $objetivo = min($objetivo, $maxPosible);

        $usadas = []; // set para llaves "alumno-lic-gen-cuat"
        $rows = [];
        $now = now();

        $intentos = 0;
        $maxIntentos = $objetivo * 50; // margen para evitar loops eternos

        while (count($rows) < $objetivo && $intentos < $maxIntentos) {
            $intentos++;

            $alumnoId = $alumnos->random();
            $licId = $licenciaturas->random();
            $genId = $generaciones->random();
            $cuatId = $cuatrimestres->random();

            $key = "{$alumnoId}-{$licId}-{$genId}-{$cuatId}";

            // ✅ Si ya la generamos en esta corrida, saltar
            if (isset($usadas[$key])) {
                continue;
            }

            // ✅ Si ya existe en BD (por si corres seed varias veces), saltar
            $existe = DB::table('inscripciones')
                ->where('alumno_id', $alumnoId)
                ->where('licenciatura_id', $licId)
                ->where('generacion_id', $genId)
                ->where('cuatrimestre_id', $cuatId)
                ->exists();

            if ($existe) {
                $usadas[$key] = true;
                continue;
            }

            $usadas[$key] = true;

            $rows[] = [
                'alumno_id' => $alumnoId,
                'licenciatura_id' => $licId,
                'generacion_id' => $genId,
                'cuatrimestre_id' => $cuatId,
                'status' => (bool) random_int(0, 9) < 9, // 90% true
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
