<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ListaAlumnosProfesorExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithEvents, WithCustomStartCell
{
    protected Collection $alumnos;
    protected string $profesor;
    protected string $materia;
    protected string $clave;
    protected string $licenciatura;
    protected string $cuatrimestre;
    protected string $generacion;

    public function __construct(
        Collection $alumnos,
        string $profesor,
        string $materia,
        string $clave,
        string $licenciatura,
        string $cuatrimestre,
        string $generacion
    ) {
        $this->alumnos = $alumnos;
        $this->profesor = $profesor;
        $this->materia = $materia;
        $this->clave = $clave;
        $this->licenciatura = $licenciatura;
        $this->cuatrimestre = $cuatrimestre;
        $this->generacion = $generacion;
    }

    public function startCell(): string
    {
        // Aquí se indica que la tabla de encabezados y alumnos empieza en la fila 10
        return 'A10';
    }

    public function collection()
    {
        return $this->alumnos->values()->map(function ($alumno, $index) {
            return [
                'numero' => $index + 1,
                'matricula' => $alumno->matricula ?? 'Sin matrícula',
                'nombre_completo' => trim(
                    ($alumno->nombre ?? '') . ' ' .
                    ($alumno->apellido_paterno ?? '') . ' ' .
                    ($alumno->apellido_materno ?? '')
                ),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No.',
            'Matrícula',
            'Nombre completo',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            10 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1D4ED8'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                $totalAlumnos = $this->alumnos->count();
                $filaEncabezadoTabla = 10;
                $filaInicioDatos = 11;
                $ultimaFila = $filaEncabezadoTabla + $totalAlumnos;

                // Título principal
                $sheet->mergeCells('A1:C1');
                $sheet->setCellValue('A1', 'LISTA DE ALUMNOS POR MATERIA');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => '0F172A'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(26);

                // Subtítulo
                $sheet->mergeCells('A2:C2');
                $sheet->setCellValue('A2', 'Sistema Web de Control Escolar · Exportación académica');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'size' => 10,
                        'color' => ['rgb' => '475569'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Bloque izquierdo
                $sheet->setCellValue('A4', 'Profesor');
                $sheet->setCellValue('B4', $this->profesor);

                $sheet->setCellValue('A5', 'Materia');
                $sheet->setCellValue('B5', $this->materia);

                $sheet->setCellValue('A6', 'Clave');
                $sheet->setCellValue('B6', $this->clave);

                $sheet->setCellValue('A7', 'Licenciatura');
                $sheet->setCellValue('B7', $this->licenciatura);

                // Bloque derecho
                $sheet->setCellValue('C4', 'Cuatrimestre');
                $sheet->setCellValue('C5', $this->cuatrimestre);

                $sheet->setCellValue('C6', 'Generación');
                $sheet->setCellValue('C7', $this->generacion);

                $sheet->setCellValue('C8', 'Total de alumnos: ' . $totalAlumnos);

                // Estilos de etiquetas
                $sheet->getStyle('A4:A7')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '1D4ED8'],
                    ],
                ]);

                $sheet->getStyle('C4:C7')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '1D4ED8'],
                    ],
                ]);

                // Estilo bloque izquierdo
                $sheet->getStyle('A4:B7')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'EFF6FF'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'BFDBFE'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Estilo bloque derecho
                $sheet->getStyle('C4:C8')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8FAFC'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CBD5E1'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Bordes de la tabla
                if ($totalAlumnos > 0) {
                    $sheet->getStyle('A10:C' . $ultimaFila)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'CBD5E1'],
                            ],
                        ],
                    ]);
                }

                // Filas alternadas
                for ($fila = $filaInicioDatos; $fila <= $ultimaFila; $fila++) {
                    if ($fila % 2 === 0) {
                        $sheet->getStyle('A' . $fila . ':C' . $fila)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F8FAFC'],
                            ],
                        ]);
                    }
                }

                // Alineaciones
                $sheet->getStyle('A10:B' . max($ultimaFila, 10))
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle('A10:C' . max($ultimaFila, 10))
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER);

                if ($totalAlumnos > 0) {
                    $sheet->getStyle('C11:C' . $ultimaFila)
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_LEFT);
                }

                // Anchos
                $sheet->getColumnDimension('A')->setWidth(8);
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(48);

                // Congelar justo debajo del encabezado de tabla
                $sheet->freezePane('A11');
            },
        ];
    }
}
