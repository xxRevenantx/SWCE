<?php

namespace App\Livewire\Estudiante;

use App\Models\Alumno;
use App\Models\AsignacionMateria;
use App\Models\Calificacion;
use App\Models\Horario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    // Datos principales del encabezado.
    public string $nombre_estudiante = '';
    public string $sexo_estudiante = '';
    public string $matricula = 'Sin matrícula';
    public string $licenciatura = 'Sin licenciatura';
    public string $cuatrimestre = 'Sin cuatrimestre';
    public string $estado_inscripcion = 'Sin inscripción';
    public string $promedio_general = '0.0';

    // Arreglos que llenan el tablero.
    public array $resumen = [];
    public array $documentacion = [];
    public array $calificaciones_recientes = [];
    public array $accesos_rapidos = [];
    public array $progreso_academico = [];

    // Datos para ApexCharts.
    public array $grafica_calificaciones = [];
    public array $grafica_progreso = [];

    // Ids de apoyo.
    public ?int $alumno_id = null;
    public ?int $inscripcion_id = null;

    public function mount(): void
    {
        // Aquí se carga toda la información del dashboard.
        $this->cargarDatos();
    }

    public function cargarDatos(): void
    {
        // Aquí se obtiene el usuario autenticado.
        $usuario = Auth::user();

        if (!$usuario) {
            return;
        }

        // Aquí se busca al alumno relacionado con el usuario.
        $alumno = Alumno::query()
            ->with([
                'datosEscolares',
                'documentacion',
                'inscripciones' => function ($query) {
                    $query->with(['licenciatura', 'generacion', 'cuatrimestre'])
                        ->latest('id');
                },
            ])
            ->where('user_id', $usuario->id)
            ->first();

        // Si no existe el alumno, solo se muestran datos básicos.
        if (!$alumno) {
            $this->nombre_estudiante = $usuario->username ?? 'Estudiante';
            $this->cargarAccesosRapidos();
            $this->cargarAvisos();
            return;
        }

        // Aquí se guardan los datos principales del alumno.
        $this->alumno_id = $alumno->id;
        $this->nombre_estudiante = trim(
            ($alumno->nombre ?? '') . ' ' .
                ($alumno->apellido_paterno ?? '') . ' ' .
                ($alumno->apellido_materno ?? '')
        );

        // Sexo del alumno
        $this->sexo_estudiante = $alumno->sexo;


        $this->matricula = $alumno->datosEscolares?->matricula ?? 'Sin matrícula';

        // Aquí se toma la inscripción más reciente.
        $inscripcionActual = $alumno->inscripciones->first();

        if ($inscripcionActual) {
            $this->inscripcion_id = $inscripcionActual->id;
            $this->licenciatura = $inscripcionActual->licenciatura?->nombre ?? 'Sin licenciatura';

            $this->cuatrimestre = $inscripcionActual->cuatrimestre?->nombre_cuatrimestre
                ?? (string) ($inscripcionActual->cuatrimestre?->no_cuatrimestre ?? 'Sin cuatrimestre');

            $this->estado_inscripcion = (bool) $inscripcionActual->status ? 'Activa' : 'Inactiva';
        }

        // Aquí se llenan todos los bloques del tablero.
        $this->cargarResumen($inscripcionActual);
        $this->cargarDocumentacion($alumno);
        $this->cargarCalificacionesRecientes();
        $this->cargarAccesosRapidos();
        $this->cargarProgresoAcademico($inscripcionActual);
        $this->cargarGraficaCalificaciones();
        $this->cargarGraficaProgreso();
        $this->cargarAvisos();
    }

    protected function cargarResumen($inscripcionActual): void
    {
        // Aquí se definen valores iniciales.
        $promedio = 0;
        $materiasInscritas = 0;
        $materiasAprobadas = 0;
        $materiasPendientes = 0;

        if ($inscripcionActual) {
            // Aquí se obtienen solo calificaciones de materias calificables.
            $calificaciones = Calificacion::query()
                ->with('asignacionMateria.materia')
                ->where('inscripcion_id', $inscripcionActual->id)
                ->whereHas('asignacionMateria.materia', function ($query) {
                    $query->where('calificable', 'si');
                })
                ->get();

            $calificacionesConValor = $calificaciones->filter(function ($item) {
                return $item->calificacion !== null;
            });

            // Aquí se calcula el promedio general solo con materias calificables.
            if ($calificacionesConValor->count() > 0) {
                $promedioReal = (float) $calificacionesConValor->avg('calificacion');

                // Aquí corto el promedio a un decimal sin redondear.
                $promedio = floor($promedioReal * 10) / 10;
            } else {
                $promedio = 0;
            }

            // Aquí se cuentan solo materias calificables del cuatrimestre actual.
            $materiasInscritas = AsignacionMateria::query()
                ->where('licenciatura_id', $inscripcionActual->licenciatura_id)
                ->where('cuatrimestre_id', $inscripcionActual->cuatrimestre_id)
                ->whereHas('materia', function ($query) {
                    $query->where('calificable', 'si');
                })
                ->count();

            // Aquí se toma 6 como mínima aprobatoria.
            $materiasAprobadas = $calificacionesConValor->where('calificacion', '>=', 6)->count();

            // Aquí se calculan las materias pendientes.
            $materiasPendientes = max($materiasInscritas - $calificacionesConValor->count(), 0);
        }

        $this->promedio_general = number_format($promedio, 1);

        // Aquí se arman las tarjetas principales.
        $this->resumen = [
            [
                'titulo' => 'Promedio general',
                'valor' => $this->promedio_general,
                'icono' => 'academic-cap',
                'color' => 'bg-gradient-to-br from-[#f8b4a6] via-[#f59db0] to-[#ef7f9f]',
            ],
            [
                'titulo' => 'Materias inscritas',
                'valor' => (string) $materiasInscritas,
                'icono' => 'book-open',
                'color' => 'bg-gradient-to-br from-[#58a8f5] via-[#3d8ee9] to-[#2b72d6]',
            ],
            [
                'titulo' => 'Materias aprobadas',
                'valor' => (string) $materiasAprobadas,
                'icono' => 'check-badge',
                'color' => 'bg-gradient-to-br from-[#66d7c8] via-[#3ec9b7] to-[#28bfa8]',
            ],
            [
                'titulo' => 'Pendientes',
                'valor' => (string) $materiasPendientes,
                'icono' => 'clock',
                'color' => 'bg-gradient-to-br from-[#b197fc] via-[#9b7cf7] to-[#7c5cf2]',
            ],
        ];
    }



    protected function cargarDocumentacion(Alumno $alumno): void
    {
        // Aquí se obtiene la documentación del alumno.
        $documentacion = $alumno->documentacion;

        $this->documentacion = [
            [
                'nombre' => 'CURP',
                'estado' => !empty($documentacion?->url_curp) ? 'Entregado' : 'Pendiente',
            ],
            [
                'nombre' => 'Acta de nacimiento',
                'estado' => !empty($documentacion?->url_acta_nacimiento) ? 'Entregado' : 'Pendiente',
            ],
            [
                'nombre' => 'Certificado de estudios',
                'estado' => !empty($documentacion?->url_certificado_estudios) ? 'Entregado' : 'Pendiente',
            ],
        ];
    }

    protected function cargarCalificacionesRecientes(): void
    {
        // Aquí se limpia el arreglo antes de volver a llenarlo.
        $this->calificaciones_recientes = [];

        if (!$this->inscripcion_id) {
            return;
        }

        // Aquí se obtienen las últimas calificaciones solo de materias calificables.
        $calificaciones = Calificacion::query()
            ->with('asignacionMateria.materia')
            ->where('inscripcion_id', $this->inscripcion_id)
            ->whereNotNull('calificacion')
            ->whereHas('asignacionMateria.materia', function ($query) {
                $query->where('calificable', 'si');
            })
            ->latest('fecha_captura')
            ->latest('id')
            ->take(5)
            ->get();

        $this->calificaciones_recientes = $calificaciones->map(function ($calificacion) {
            return [
                'materia' => $calificacion->asignacionMateria?->materia?->nombre ?? 'Sin materia',
                'calificacion' => number_format((float) $calificacion->calificacion, 1),
            ];
        })->toArray();
    }

    protected function cargarAccesosRapidos(): void
    {
        // Aquí se definen los accesos rápidos del estudiante.
        $this->accesos_rapidos = [
            [
                'titulo' => 'Mi perfil',
                'descripcion' => 'Consulta tus datos personales y escolares.',
                'ruta' => route('estudiante.perfil'),
                'icono' => 'user',
            ],
            [
                'titulo' => 'Mi expediente',
                'descripcion' => 'Descarga tu expediente en PDF.',
                'ruta' => route('estudiante.pdf.mi-expediente'),
                'icono' => 'document-text',
            ],
        ];
    }

    protected function cargarProgresoAcademico($inscripcionActual): void
    {
        // Aquí se definen valores iniciales.
        $materiasCursadas = 0;
        $materiasTotales = 0;
        $porcentaje = 0;

        if ($inscripcionActual) {
            // Aquí se cuentan solo las materias calificables de la licenciatura.
            $materiasTotales = AsignacionMateria::query()
                ->where('licenciatura_id', $inscripcionActual->licenciatura_id)
                ->whereHas('materia', function ($query) {
                    $query->where('calificable', 'si');
                })
                ->count();

            // Aquí se cuentan solo las materias calificables ya evaluadas.
            $materiasCursadas = Calificacion::query()
                ->where('inscripcion_id', $inscripcionActual->id)
                ->whereNotNull('calificacion')
                ->whereHas('asignacionMateria.materia', function ($query) {
                    $query->where('calificable', 'si');
                })
                ->count();

            // Aquí se calcula el porcentaje.
            $porcentaje = $materiasTotales > 0
                ? (int) round(($materiasCursadas / $materiasTotales) * 100)
                : 0;
        }

        $this->progreso_academico = [
            'materias_cursadas' => $materiasCursadas,
            'materias_totales' => $materiasTotales,
            'porcentaje' => $porcentaje,
        ];
    }

    protected function cargarGraficaCalificaciones(): void
    {
        // Aquí se limpian los datos de la gráfica.
        $this->grafica_calificaciones = [
            'categorias' => [],
            'series' => [],
        ];

        if (!$this->inscripcion_id) {
            return;
        }

        // Aquí se obtienen calificaciones solo de materias calificables.
        $calificaciones = Calificacion::query()
            ->with('asignacionMateria.materia')
            ->where('inscripcion_id', $this->inscripcion_id)
            ->whereNotNull('calificacion')
            ->whereHas('asignacionMateria.materia', function ($query) {
                $query->where('calificable', 'si');
            })
            ->latest('fecha_captura')
            ->latest('id')
            ->take(5)
            ->get()
            ->reverse()
            ->values();

        $this->grafica_calificaciones = [
            'categorias' => $calificaciones->map(function ($calificacion) {
                return $calificacion->asignacionMateria?->materia?->nombre ?? 'Sin materia';
            })->toArray(),
            'series' => $calificaciones->map(function ($calificacion) {
                return (float) $calificacion->calificacion;
            })->toArray(),
        ];
    }

    protected function cargarGraficaProgreso(): void
    {
        // Aquí se preparan los datos de la gráfica de progreso.
        $materiasCursadas = (int) ($this->progreso_academico['materias_cursadas'] ?? 0);
        $materiasTotales = (int) ($this->progreso_academico['materias_totales'] ?? 0);

        $materiasPendientes = max($materiasTotales - $materiasCursadas, 0);

        $this->grafica_progreso = [
            'labels' => ['Completadas', 'Pendientes'],
            'series' => [$materiasCursadas, $materiasPendientes],
        ];
    }

    protected function cargarAvisos(): void
    {
        // Aquí se reinician los avisos.
        $this->avisos = [];

        foreach ($this->documentacion as $documento) {
            if (($documento['estado'] ?? '') === 'Pendiente') {
                $this->avisos[] = [
                    'titulo' => 'Documento pendiente',
                    'descripcion' => 'Aún no se ha cargado: ' . $documento['nombre'] . '.',
                    'fecha' => now()->format('d/m/Y'),
                ];
            }
        }

        if ($this->estado_inscripcion !== 'Activa') {
            $this->avisos[] = [
                'titulo' => 'Estado de inscripción',
                'descripcion' => 'Tu inscripción actualmente aparece como ' . mb_strtolower($this->estado_inscripcion) . '.',
                'fecha' => now()->format('d/m/Y'),
            ];
        }

        if (empty($this->avisos)) {
            $this->avisos[] = [
                'titulo' => 'Sin avisos importantes',
                'descripcion' => 'Por ahora no hay pendientes visibles en el sistema.',
                'fecha' => now()->format('d/m/Y'),
            ];
        }
    }

    public function obtenerClaseBadgeEstado(string $estado): string
    {
        // Aquí se devuelven estilos según el estado del documento.
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
