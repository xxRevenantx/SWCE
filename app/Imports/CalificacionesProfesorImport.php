<?php

namespace App\Imports;

use App\Models\Calificacion;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CalificacionesImport implements ToCollection, WithHeadingRow
{
    protected $periodoId;
    protected $errores = [];

    public function __construct($periodoId)
    {
        $this->periodoId = $periodoId;
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
                 * Se valida que los identificadores internos existan.
                 * Estos campos no se actualizan, solo se usan para localizar
                 * la calificación correcta.
                 */
                if (!$inscripcionId || !$asignacionMateriaId) {
                    $this->errores[] = "Fila {$fila}: faltan identificadores internos del alumno o materia.";
                    continue;
                }

                /*
                 * Se valida que la calificación no venga vacía.
                 */
                if ($calificacion === null || $calificacion === '') {
                    $this->errores[] = "Fila {$fila}: la calificación está vacía.";
                    continue;
                }

                /*
                 * Se permite número de 0 a 10.
                 * Si también usas AC, ED o RA, se aceptan como códigos válidos.
                 */
                $calificacion = strtoupper(trim((string) $calificacion));

                $esNumeroValido = is_numeric($calificacion)
                    && $calificacion >= 0
                    && $calificacion <= 10;

                $esCodigoValido = in_array($calificacion, ['AC', 'ED', 'RA'], true);

                if (!$esNumeroValido && !$esCodigoValido) {
                    $this->errores[] = "Fila {$fila}: la calificación no es válida.";
                    continue;
                }

                /*
                 * Se busca la calificación existente usando los identificadores.
                 * No se permite modificar inscripcion_id ni asignacion_materia_id.
                 */
                $registro = Calificacion::where('inscripcion_id', $inscripcionId)
                    ->where('asignacion_materia_id', $asignacionMateriaId)
                    ->where('periodo_id', $this->periodoId)
                    ->first();

                if (!$registro) {
                    $this->errores[] = "Fila {$fila}: no se encontró una calificación relacionada con ese alumno y materia.";
                    continue;
                }

                /*
                 * Solo se actualiza la columna calificacion.
                 */
                $registro->update([
                    'calificacion' => $calificacion,
                ]);
            }
        });
    }

    public function getErrores(): array
    {
        return $this->errores;
    }
}
