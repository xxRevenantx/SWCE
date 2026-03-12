<?php

namespace App\Livewire\Estudiante;

use Livewire\Component;

class Calificaciones extends Component
{
    // Texto de búsqueda para filtrar materias.
    public string $buscar = '';

    // Resumen general del alumno.
    public string $nombre_estudiante = 'Carlos Alberto Núñez Pérez';
    public string $matricula = '20260001';
    public string $licenciatura = 'Ingeniería en Desarrollo de Software';
    public string $cuatrimestre = '8° Cuatrimestre';
    public string $promedio_general = '9.2';

    // Resumen visual del módulo.
    public int $total_materias = 0;
    public int $materias_aprobadas = 0;
    public int $materias_reprobadas = 0;
    public int $materias_pendientes = 0;

    // Arreglo principal de calificaciones.
    public array $calificaciones = [];

    public function mount(): void
    {
        $this->calificaciones = [
            [
                'clave' => 'IDS-801',
                'materia' => 'Desarrollo Web Avanzado',
                'profesor' => 'Mtro. Edgar García Basilio',
                'parcial_1' => 9.5,
                'parcial_2' => 9.0,
                'parcial_3' => 10.0,
                'final' => 9.5,
                'asistencia' => 96,
                'estado' => 'Aprobada',
            ],
            [
                'clave' => 'IDS-802',
                'materia' => 'Bases de Datos Distribuidas',
                'profesor' => 'Dra. Mariana López',
                'parcial_1' => 8.4,
                'parcial_2' => 8.8,
                'parcial_3' => 9.1,
                'final' => 8.7,
                'asistencia' => 92,
                'estado' => 'Aprobada',
            ],
            [
                'clave' => 'IDS-803',
                'materia' => 'Seguridad Informática',
                'profesor' => 'Ing. Juan Carlos Martínez',
                'parcial_1' => 7.0,
                'parcial_2' => 6.8,
                'parcial_3' => 7.5,
                'final' => 7.1,
                'asistencia' => 88,
                'estado' => 'Aprobada',
            ],
            [
                'clave' => 'IDS-804',
                'materia' => 'Programación Móvil',
                'profesor' => 'Mtra. Andrea Salgado',
                'parcial_1' => 5.9,
                'parcial_2' => 6.2,
                'parcial_3' => 5.8,
                'final' => 5.9,
                'asistencia' => 79,
                'estado' => 'Reprobada',
            ],
            [
                'clave' => 'IDS-805',
                'materia' => 'Inteligencia Artificial',
                'profesor' => 'Dr. Rafael Mendoza',
                'parcial_1' => null,
                'parcial_2' => null,
                'parcial_3' => null,
                'final' => null,
                'asistencia' => 0,
                'estado' => 'Pendiente',
            ],
        ];

        $this->calcularResumen();
    }

    // Calcula las tarjetas de resumen.
    public function calcularResumen(): void
    {
        $this->total_materias = count($this->calificaciones);

        $this->materias_aprobadas = collect($this->calificaciones)
            ->where('estado', 'Aprobada')
            ->count();

        $this->materias_reprobadas = collect($this->calificaciones)
            ->where('estado', 'Reprobada')
            ->count();

        $this->materias_pendientes = collect($this->calificaciones)
            ->where('estado', 'Pendiente')
            ->count();
    }

    // Devuelve las materias filtradas por búsqueda.
    public function getCalificacionesFiltradasProperty(): array
    {
        if (trim($this->buscar) === '') {
            return $this->calificaciones;
        }

        $texto = mb_strtolower(trim($this->buscar));

        return collect($this->calificaciones)
            ->filter(function ($fila) use ($texto) {
                return str_contains(mb_strtolower($fila['clave']), $texto)
                    || str_contains(mb_strtolower($fila['materia']), $texto)
                    || str_contains(mb_strtolower($fila['profesor']), $texto)
                    || str_contains(mb_strtolower($fila['estado']), $texto);
            })
            ->values()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.estudiante.calificaciones');
    }
}
