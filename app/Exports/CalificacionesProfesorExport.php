<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CalificacionesProfesorExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function __construct(private Collection $registros)
    {
    }

    public function collection(): Collection
    {
        return $this->registros;
    }

    public function headings(): array
    {
        return [
            'inscripcion_id',
            'asignacion_materia_id',
            'matricula',
            'alumno',
            'materia',
            'licenciatura',
            'cuatrimestre',
            'generacion',
            'calificacion',
            'fecha_captura',
        ];
    }

    public function map($registro): array
    {
        $alumno = trim(
            ($registro->alumno_nombre ?? '') . ' ' .
            ($registro->alumno_apellido_paterno ?? '') . ' ' .
            ($registro->alumno_apellido_materno ?? '')
        );

        return [
            $registro->inscripcion_id,
            $registro->asignacion_materia_id,
            $registro->matricula,
            $alumno,
            $registro->materia,
            $registro->licenciatura,
            $registro->cuatrimestre,
            $registro->generacion,
            $registro->calificacion,
            $registro->fecha_captura,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal('center');

        return [];
    }
}
