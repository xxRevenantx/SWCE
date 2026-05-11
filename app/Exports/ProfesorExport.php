<?php

namespace App\Exports;

use App\Models\Calificacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CalificacionesExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $grupoId;
    protected $periodoId;

    public function __construct($grupoId, $periodoId)
    {
        $this->grupoId = $grupoId;
        $this->periodoId = $periodoId;
    }

    public function collection()
    {
        return Calificacion::query()
            ->with([
                'inscripcion',
                'asignacionMateria',
            ])
            ->where('grupo_id', $this->grupoId)
            ->where('periodo_id', $this->periodoId)
            ->get();
    }

    public function headings(): array
    {
        return [
            'inscripcion_id',
            'asignacion_materia_id',
            'alumno',
            'materia',
            'calificacion',
        ];
    }

    public function map($calificacion): array
    {
        return [
            $calificacion->inscripcion_id,
            $calificacion->asignacion_materia_id,
            $calificacion->inscripcion?->nombre_completo ?? '',
            $calificacion->asignacionMateria?->materia ?? '',
            $calificacion->calificacion,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $hoja = $event->sheet->getDelegate();

                /*
                 * Se protege la hoja para evitar que el docente modifique
                 * columnas internas del sistema.
                 */
                $hoja->getProtection()->setSheet(true);

                /*
                 * Se bloquean todas las columnas por defecto.
                 * Esto protege inscripcion_id, asignacion_materia_id,
                 * alumno y materia.
                 */
                $hoja->getStyle('A:D')->getProtection()->setLocked(
                    \PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED
                );

                /*
                 * Solo se desbloquea la columna E, donde el docente
                 * debe capturar o modificar la calificación.
                 */
                $hoja->getStyle('E:E')->getProtection()->setLocked(
                    \PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED
                );

                /*
                 * Se aplica estilo visual para indicar qué columna puede editarse.
                 */
                $hoja->getStyle('A1:E1')->getFont()->setBold(true);
                $hoja->getColumnDimension('A')->setWidth(18);
                $hoja->getColumnDimension('B')->setWidth(25);
                $hoja->getColumnDimension('C')->setWidth(35);
                $hoja->getColumnDimension('D')->setWidth(35);
                $hoja->getColumnDimension('E')->setWidth(15);

                /*
                 * Se congela la primera fila para que los encabezados
                 * permanezcan visibles durante la captura.
                 */
                $hoja->freezePane('A2');
            },
        ];
    }
}
