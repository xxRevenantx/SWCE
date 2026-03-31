<?php

namespace App\Exports;

use App\Models\Profesor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class ProfesorExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    protected $profesores;

    public function __construct($profesores)
    {
        $this->profesores = $profesores;
    }

    public function collection()
    {
        return $this->profesores->values()->map(function ($prof, $index) {
            return [
                'numero' => $index + 1,
                'nombre' => $prof->nombre,
                'apellido_paterno' => $prof->apellido_paterno,
                'apellido_materno' => $prof->apellido_materno,
                'CURP' => $prof->CURP,
                'telefono' => $prof->telefono,
                'perfil' => $prof->perfil,
                'status' => $prof->status ? 'Activo' : 'Inactivo',
                'created_at' => $prof->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'N°',
            'Nombre',
            'Apellido Paterno',
            'Apellido Materno',
            'CURP',
            'Teléfono',
            'Perfil',
            'Status',
            'Fecha de Creación',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Encabezado con fondo verde y texto blanco
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50'],
            ],
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $rowCount = $this->profesores->count();
                $cellRange = 'A1:I' . ($rowCount + 1);

                // Bordes para toda la tabla
                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
