<?php

namespace App\Imports;

use App\Models\Calificacion;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CalificacionesProfesorImport implements ToCollection, WithHeadingRow
{
    protected int $profesorId;

    protected array $errores = [];

    public function __construct(int $profesorId)
    {
        $this->profesorId = $profesorId;
    }

    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $index => $row) {
                $fila = $index + 2;

                $inscripcionId = $row['inscripcion_id'] ?? null;
                $asignacionMateriaId = $row['asignacion_materia_id'] ?? null;
                $calificacion = $row['calificacion'] ?? null;

                /*
                 * Se revisa que existan los identificadores internos.
                 * Estos campos solo sirven para ubicar al alumno y la materia.
                 */
                if (empty($inscripcionId) || empty($asignacionMateriaId)) {
                    $this->errores[] = "Fila {$fila}: faltan los identificadores internos del alumno o la materia.";
                    continue;
                }

                /*
                 * Se revisa que la calificación no venga vacía.
                 */
                if ($calificacion === null || $calificacion === '') {
                    $this->errores[] = "Fila {$fila}: la calificación está vacía.";
                    continue;
                }

                /*
                 * Se limpia el valor capturado.
                 */
                $calificacion = trim((string) $calificacion);
                $calificacion = str_replace(',', '.', $calificacion);

                /*
                 * Para nivel superior se acepta únicamente calificación numérica de 0 a 10.
                 */
                if (!is_numeric($calificacion)) {
                    $this->errores[] = "Fila {$fila}: la calificación debe ser numérica.";
                    continue;
                }

                $calificacion = round((float) $calificacion, 2);

                if ($calificacion < 0 || $calificacion > 10) {
                    $this->errores[] = "Fila {$fila}: la calificación debe estar entre 0 y 10.";
                    continue;
                }

                /*
                 * Se valida que la materia asignada pertenezca al profesor autenticado.
                 * Esto evita que el docente manipule el archivo y capture materias ajenas.
                 */
                $asignacion = DB::table('asignacion_materias')
                    ->where('id', (int) $asignacionMateriaId)
                    ->where('profesor_id', $this->profesorId)
                    ->first();

                if (!$asignacion) {
                    $this->errores[] = "Fila {$fila}: la materia asignada no pertenece al profesor autenticado.";
                    continue;
                }

                /*
                 * Se valida que la inscripción exista y corresponda al mismo contexto académico.
                 * Así se evita relacionar una materia con un alumno que no corresponde.
                 */
                $inscripcionExiste = DB::table('inscripciones')
                    ->where('id', (int) $inscripcionId)
                    ->where('status', 1)
                    ->where('licenciatura_id', $asignacion->licenciatura_id)
                    ->where('cuatrimestre_id', $asignacion->cuatrimestre_id)
                    ->exists();

                if (!$inscripcionExiste) {
                    $this->errores[] = "Fila {$fila}: el alumno no corresponde a la materia asignada.";
                    continue;
                }

                /*
                 * Solo se actualiza o crea la calificación.
                 * No se modifican inscripcion_id ni asignacion_materia_id.
                 */
                Calificacion::updateOrCreate(
                    [
                        'inscripcion_id' => (int) $inscripcionId,
                        'asignacion_materia_id' => (int) $asignacionMateriaId,
                    ],
                    [
                        'calificacion' => $calificacion,
                        'fecha_captura' => now()->toDateString(),
                    ]
                );
            }
        });
    }

    public function getErrores(): array
    {
        return $this->errores;
    }
}
