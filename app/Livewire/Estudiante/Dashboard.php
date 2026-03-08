<?php

namespace App\Livewire\Estudiante;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public string $nombre_estudiante = '';
    public string $matricula = 'Sin matrícula';
    public string $licenciatura = 'Sin licenciatura';
    public string $cuatrimestre = 'Sin cuatrimestre';
    public string $estado_inscripcion = 'Activa';
    public string $promedio_general = '0.0';

    public array $resumen = [];
    public array $clases_hoy = [];
    public array $avisos = [];
    public array $documentacion = [];
    public array $calificaciones_recientes = [];
    public array $accesos_rapidos = [];
    public array $progreso_academico = [];

    public function mount(): void
    {
        $this->cargarDatos();
    }

    public function cargarDatos(): void
    {
        $usuario = Auth::user();

        $this->nombre_estudiante = $usuario?->name ?? 'Estudiante';

        /*
        |--------------------------------------------------------------------------
        | Estos valores están como ejemplo visual.
        | Después aquí se pueden reemplazar con consultas reales.
        |--------------------------------------------------------------------------
        */
        $this->matricula = '2026-001';
        $this->licenciatura = 'Ingeniería en Desarrollo de Software';
        $this->cuatrimestre = '5° Cuatrimestre';
        $this->estado_inscripcion = 'Activa';
        $this->promedio_general = '9.4';

        $this->resumen = [
            [
                'titulo' => 'Promedio general',
                'valor' => $this->promedio_general,
                'icono' => 'academic-cap',
                'color' => 'from-sky-500 to-blue-600',
            ],
            [
                'titulo' => 'Materias inscritas',
                'valor' => '6',
                'icono' => 'book-open',
                'color' => 'from-emerald-500 to-green-600',
            ],
            [
                'titulo' => 'Materias aprobadas',
                'valor' => '18',
                'icono' => 'check-badge',
                'color' => 'from-violet-500 to-fuchsia-600',
            ],
            [
                'titulo' => 'Pendientes',
                'valor' => '2',
                'icono' => 'clock',
                'color' => 'from-amber-500 to-orange-500',
            ],
        ];

        $this->clases_hoy = [
            [
                'hora' => '08:00 - 09:00',
                'materia' => 'Programación Web',
                'profesor' => 'Ing. Edgar García',
                'aula' => 'Aula 3',
            ],
            [
                'hora' => '09:00 - 10:00',
                'materia' => 'Base de Datos',
                'profesor' => 'Mtra. Laura Pérez',
                'aula' => 'Lab. 2',
            ],
        ];

        $this->avisos = [
            [
                'titulo' => 'Publicación de calificaciones',
                'descripcion' => 'Las calificaciones del parcial estarán disponibles el 15 de marzo.',
                'fecha' => '15 mar 2026',
            ],
            [
                'titulo' => 'Entrega de documentos',
                'descripcion' => 'Falta cargar el comprobante de domicilio actualizado.',
                'fecha' => '20 mar 2026',
            ],
        ];

        $this->documentacion = [
            [
                'nombre' => 'CURP',
                'estado' => 'Entregado',
            ],
            [
                'nombre' => 'Acta de nacimiento',
                'estado' => 'Entregado',
            ],
            [
                'nombre' => 'Certificado de estudios',
                'estado' => 'Entregado',
            ],
            [
                'nombre' => 'Comprobante de domicilio',
                'estado' => 'Pendiente',
            ],
        ];

        $this->calificaciones_recientes = [
            [
                'materia' => 'Programación Web',
                'calificacion' => '9.8',
            ],
            [
                'materia' => 'Base de Datos',
                'calificacion' => '9.2',
            ],
            [
                'materia' => 'Ingeniería de Software',
                'calificacion' => '9.1',
            ],
        ];

        $this->accesos_rapidos = [
            [
                'titulo' => 'Mi perfil',
                'descripcion' => 'Consulta tus datos personales y escolares.',
                'ruta' => '#',
                'icono' => 'user',
            ],
            [
                'titulo' => 'Horario',
                'descripcion' => 'Revisa tus clases programadas.',
                'ruta' => '#',
                'icono' => 'calendar-days',
            ],
            [
                'titulo' => 'Calificaciones',
                'descripcion' => 'Consulta tus evaluaciones.',
                'ruta' => '#',
                'icono' => 'document-text',
            ],
            [
                'titulo' => 'Documentos',
                'descripcion' => 'Verifica tus archivos entregados.',
                'ruta' => '#',
                'icono' => 'folder',
            ],
        ];

        $this->progreso_academico = [
            'materias_cursadas' => 18,
            'materias_totales' => 36,
            'porcentaje' => 50,
        ];
    }

    public function obtenerClaseBadgeEstado(string $estado): string
    {
        return match ($estado) {
            'Entregado' => 'bg-emerald-100 text-emerald-700 ring-1 ring-inset ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20',
            'Pendiente' => 'bg-amber-100 text-amber-700 ring-1 ring-inset ring-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-500/20',
            default => 'bg-neutral-100 text-neutral-700 ring-1 ring-inset ring-neutral-200 dark:bg-neutral-500/10 dark:text-neutral-300 dark:ring-neutral-500/20',
        };
    }

    public function render()
    {
        return view('livewire.estudiante.dashboard');
    }
}
