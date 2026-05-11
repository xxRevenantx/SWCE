<?php

namespace App\Imports;

use App\Models\Calificacion;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Throwable;

class CalificacionesProfesorImport implements ToCollection, WithHeadingRow
{
    public array $errores = [];
    public int $actualizadas = 0;

    public function __construct(private int $profesorId)
    {
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $indice => $row) {
            $fila = $indice + 2;

            try {
                $inscripcionId = (int) ($row['inscripcion_id'] ?? 0);
                $asignacionMateriaId = (int) ($row['asignacion_materia_id'] ?? 0);
                $calificacion = $row['calificacion'] ?? null;

                if ($inscripcionId <= 0 || $asignacionMateriaId <= 0) {
                    $this->errores[] = "Fila {$fila}: faltan inscripcion_id o asignacion_materia_id.";
                    continue;
                }

                if ($calificacion === null || $calificacion === '') {
                    Calificacion::where('inscripcion_id', $inscripcionId)
                        ->where('asignacion_materia_id', $asignacionMateriaId)
                        ->delete();
                    $this->actualizadas++;
                    continue;
                }

                if (!is_numeric($calificacion) || (float) $calificacion < 0 || (float) $calificacion > 10) {
                    $this->errores[] = "Fila {$fila}: la calificación debe estar entre 0 y 10.";
                    continue;
                }

                $perteneceAlProfesor = DB::table('asignacion_materias')
                    ->where('id', $asignacionMateriaId)
                    ->where('profesor_id', $this->profesorId)
                    ->exists();

                if (!$perteneceAlProfesor) {
                    $this->errores[] = "Fila {$fila}: la materia no pertenece al profesor autenticado.";
                    continue;
                }

                Calificacion::updateOrCreate(
                    [
                        'inscripcion_id' => $inscripcionId,
                        'asignacion_materia_id' => $asignacionMateriaId,
                    ],
                    [
                        'calificacion' => (float) $calificacion,
                        'fecha_captura' => now()->toDateString(),
                    ]
                );

                $this->actualizadas++;
            } catch (Throwable $e) {
                $this->errores[] = "Fila {$fila}: no se pudo procesar el registro.";
            }
        }
    }
}
