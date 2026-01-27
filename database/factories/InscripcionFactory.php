<?php

namespace Database\Factories;

use App\Models\Alumno;
use App\Models\Cuatrimestre;
use App\Models\Generacion;
use App\Models\Inscripcion;
use App\Models\Licenciatura;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inscripcion>
 */
class InscripcionFactory extends Factory
{
    protected $model = Inscripcion::class;

    public function definition(): array
    {
        // Intentamos tomar IDs existentes; si no hay, creamos con factories (si existen).
        // Si aún no tienes factories para Licenciatura/Generacion/Cuatrimestre/Alumno,
        // crea al menos seeders para esas tablas antes de usar este factory.

        $alumnoId = Alumno::query()->inRandomOrder()->value('id');
        $licId = Licenciatura::query()->inRandomOrder()->value('id');
        $genId = Generacion::query()->inRandomOrder()->value('id');
        $cuatId = Cuatrimestre::query()->inRandomOrder()->value('id');

        // Si alguno no existe y hay factory disponible, lo creamos.
        $alumnoId = $alumnoId ?? Alumno::factory()->create()->id;
        $licId = $licId ?? Licenciatura::factory()->create()->id;
        $genId = $genId ?? Generacion::factory()->create()->id;
        $cuatId = $cuatId ?? Cuatrimestre::factory()->create()->id;

        // OJO: tu tabla tiene UNIQUE compuesto, así que garantizamos combinación única.
        // Reintentamos unas cuantas veces hasta encontrar una combinación libre.
        $tries = 0;

        do {
            if ($tries > 0) {
                // re-sortea IDs si la combinación ya existe
                $alumnoId = Alumno::query()->inRandomOrder()->value('id') ?? $alumnoId;
                $licId = Licenciatura::query()->inRandomOrder()->value('id') ?? $licId;
                $genId = Generacion::query()->inRandomOrder()->value('id') ?? $genId;
                $cuatId = Cuatrimestre::query()->inRandomOrder()->value('id') ?? $cuatId;
            }

            $exists = Inscripcion::query()
                ->where('alumno_id', $alumnoId)
                ->where('licenciatura_id', $licId)
                ->where('generacion_id', $genId)
                ->where('cuatrimestre_id', $cuatId)
                ->exists();

            $tries++;
        } while ($exists && $tries < 25);

        return [
            'alumno_id' => $alumnoId,
            'licenciatura_id' => $licId,
            'generacion_id' => $genId,
            'cuatrimestre_id' => $cuatId,
            'status' => 1, // 90% true
            'fecha_inscripcion' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
        ];
    }

    public function activa(): static
    {
        return $this->state(fn() => ['status' => true]);
    }

    public function baja(): static
    {
        return $this->state(fn() => ['status' => false]);
    }

    public function fechaHoy(): static
    {
        return $this->state(fn() => ['fecha_inscripcion' => now()->toDateString()]);
    }
}
