<?php

namespace App\Livewire\Profesor;

use App\Models\Calificacion;
use App\Models\Cuatrimestre;
use App\Models\Inscripcion;
use App\Models\Licenciatura;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Calificaciones extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Filtros
    public string $buscar = '';
    public ?string $licenciatura_id = null;
    public ?string $cuatrimestre_id = null;
    public ?string $materia_id = null;
    public ?string $generacion_id = null;

    // Catálogos
    public array $licenciaturas = [];
    public array $cuatrimestres = [];
    public array $materias = [];
    public array $generaciones = [];

    // Profesor autenticado
    public ?object $profesor = null;
    public ?int $profesor_id = null;

    // Modal
    public bool $mostrarModal = false;

    // Datos de edición
    public ?int $calificacion_id = null;
    public ?int $inscripcion_id = null;
    public ?int $asignacion_materia_id = null;

    public string $alumno = '';
    public string $matricula = '';
    public string $materia = '';
    public string $licenciatura = '';
    public string $cuatrimestre = '';
    public string $generacion = '';

    public $calificacion = '';
    public string $fecha_captura = '';

    protected function rules(): array
    {
        return [
            'inscripcion_id' => ['required', 'integer'],
            'asignacion_materia_id' => ['required', 'integer'],
            'calificacion' => ['required', 'integer', 'min:0', 'max:10'],
        ];
    }

    protected $messages = [
        'calificacion.required' => 'La calificación es obligatoria.',
        'calificacion.integer' => 'La calificación debe ser un número entero.',
        'calificacion.min' => 'La calificación no puede ser menor a 0.',
        'calificacion.max' => 'La calificación no puede ser mayor a 10.',
    ];

    public function mount(): void
    {
        // Se obtiene el profesor relacionado al usuario autenticado
        $this->profesor = Profesor::where('user_id', Auth::id())->first();
        $this->profesor_id = $this->profesor?->id;

        $this->cargarFiltros();
    }

    public function updatingBuscar(): void
    {
        $this->resetPage();
    }

    public function updatedLicenciaturaId(): void
    {
        $this->cuatrimestre_id = null;
        $this->materia_id = null;
        $this->generacion_id = null;

        $this->resetPage();
        $this->cargarFiltros();
    }

    public function updatedCuatrimestreId(): void
    {
        $this->materia_id = null;
        $this->generacion_id = null;

        $this->resetPage();
        $this->cargarFiltros();
    }

    public function updatedMateriaId(): void
    {
        $this->resetPage();
    }

    public function updatedGeneracionId(): void
    {
        $this->resetPage();
    }

    public function consultaBase()
    {
        return Inscripcion::query()
            ->join('alumnos', 'inscripciones.alumno_id', '=', 'alumnos.id')
            ->join('asignacion_materias', function ($join) {
                $join->on('asignacion_materias.licenciatura_id', '=', 'inscripciones.licenciatura_id')
                    ->on('asignacion_materias.cuatrimestre_id', '=', 'inscripciones.cuatrimestre_id');
            })
            ->join('materias', 'asignacion_materias.materia_id', '=', 'materias.id')
            ->join('licenciaturas', 'inscripciones.licenciatura_id', '=', 'licenciaturas.id')
            ->join('cuatrimestres', 'inscripciones.cuatrimestre_id', '=', 'cuatrimestres.id')
            ->join('generaciones', 'inscripciones.generacion_id', '=', 'generaciones.id')
            ->leftJoin('datos_escolares', 'datos_escolares.alumno_id', '=', 'alumnos.id')
            ->leftJoin('calificaciones', function ($join) {
                $join->on('calificaciones.inscripcion_id', '=', 'inscripciones.id')
                    ->on('calificaciones.asignacion_materia_id', '=', 'asignacion_materias.id');
            })
            ->where('inscripciones.status', 1)
            ->where('asignacion_materias.profesor_id', $this->profesor_id);
    }

    public function cargarFiltros(): void
    {
        if (!$this->profesor_id) {
            $this->licenciaturas = [];
            $this->cuatrimestres = [];
            $this->materias = [];
            $this->generaciones = [];
            return;
        }

        $base = $this->consultaBase();

        $idsLicenciaturas = (clone $base)
            ->distinct()
            ->pluck('inscripciones.licenciatura_id')
            ->filter()
            ->values();

        $this->licenciaturas = Licenciatura::whereIn('id', $idsLicenciaturas)
            ->orderBy('nombre')
            ->get(['id', 'nombre'])
            ->map(function ($licenciatura) {
                return [
                    'id' => $licenciatura->id,
                    'nombre' => $licenciatura->nombre,
                ];
            })
            ->values()
            ->toArray();

        $consultaCuatrimestres = $this->consultaBase();

        if ($this->licenciatura_id) {
            $consultaCuatrimestres->where('inscripciones.licenciatura_id', (int) $this->licenciatura_id);
        }

        $idsCuatrimestres = $consultaCuatrimestres
            ->distinct()
            ->pluck('inscripciones.cuatrimestre_id')
            ->filter()
            ->values();

        $this->cuatrimestres = Cuatrimestre::whereIn('id', $idsCuatrimestres)
            ->orderBy('no_cuatrimestre')
            ->get(['id', 'nombre_cuatrimestre'])
            ->map(function ($cuatrimestre) {
                return [
                    'id' => $cuatrimestre->id,
                    'nombre_cuatrimestre' => $cuatrimestre->nombre_cuatrimestre,
                ];
            })
            ->values()
            ->toArray();

        $consultaMaterias = $this->consultaBase();

        if ($this->licenciatura_id) {
            $consultaMaterias->where('inscripciones.licenciatura_id', (int) $this->licenciatura_id);
        }

        if ($this->cuatrimestre_id) {
            $consultaMaterias->where('inscripciones.cuatrimestre_id', (int) $this->cuatrimestre_id);
        }

        $this->materias = $consultaMaterias
            ->select('materias.id', 'materias.nombre')
            ->distinct()
            ->orderBy('materias.nombre')
            ->get()
            ->map(function ($materia) {
                return [
                    'id' => $materia->id,
                    'nombre' => $materia->nombre,
                ];
            })
            ->values()
            ->toArray();

        $consultaGeneraciones = $this->consultaBase();

        if ($this->licenciatura_id) {
            $consultaGeneraciones->where('inscripciones.licenciatura_id', (int) $this->licenciatura_id);
        }

        if ($this->cuatrimestre_id) {
            $consultaGeneraciones->where('inscripciones.cuatrimestre_id', (int) $this->cuatrimestre_id);
        }

        $this->generaciones = $consultaGeneraciones
            ->select('generaciones.id', 'generaciones.generacion')
            ->distinct()
            ->orderBy('generaciones.generacion')
            ->get()
            ->map(function ($generacion) {
                return [
                    'id' => $generacion->id,
                    'generacion' => $generacion->generacion,
                ];
            })
            ->values()
            ->toArray();
    }

    public function abrirModal(
        ?int $calificacionId,
        int $inscripcionId,
        int $asignacionMateriaId,
        string $alumno,
        ?string $matricula,
        string $materia,
        string $licenciatura,
        string $cuatrimestre,
        string $generacion,
        $calificacionActual,
        ?string $fechaCaptura
    ): void {
        $this->resetValidation();

        $this->calificacion_id = $calificacionId;
        $this->inscripcion_id = $inscripcionId;
        $this->asignacion_materia_id = $asignacionMateriaId;

        $this->alumno = $alumno;
        $this->matricula = $matricula ?? 'Sin matrícula';
        $this->materia = $materia;
        $this->licenciatura = $licenciatura;
        $this->cuatrimestre = $cuatrimestre;
        $this->generacion = $generacion;

        $this->calificacion = $calificacionActual ?? '';
        $this->fecha_captura = $fechaCaptura ?: '';

        $this->mostrarModal = true;

        // Se avisa al front que el modal ya cargó los datos
        $this->dispatch('calificacion-cargada');
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;

        $this->resetValidation();

        $this->calificacion_id = null;
        $this->inscripcion_id = null;
        $this->asignacion_materia_id = null;

        $this->alumno = '';
        $this->matricula = '';
        $this->materia = '';
        $this->licenciatura = '';
        $this->cuatrimestre = '';
        $this->generacion = '';

        $this->calificacion = '';
        $this->fecha_captura = '';
    }

    public function guardarCalificacion(): void
    {
        $this->validate();

        $this->calificacion = (int) $this->calificacion;

        // Se valida que la materia pertenezca al profesor autenticado
        $materiaDelProfesor = DB::table('asignacion_materias')
            ->where('id', $this->asignacion_materia_id)
            ->where('profesor_id', $this->profesor_id)
            ->exists();

        if (!$materiaDelProfesor) {
            $this->dispatch('notificacion', [
                'tipo' => 'error',
                'mensaje' => 'No se puede guardar esta calificación.',
                'position' => 'top-end',
            ]);
            return;
        }

        Calificacion::updateOrCreate(
            [
                'inscripcion_id' => $this->inscripcion_id,
                'asignacion_materia_id' => $this->asignacion_materia_id,
            ],
            [
                'calificacion' => $this->calificacion,
                'fecha_captura' => now()->toDateString(),
            ]
        );

        $this->dispatch('notificacion', [
            'tipo' => 'success',
            'mensaje' => 'La calificación se guardó correctamente.',
            'position' => 'top-end',
        ]);

        $this->cerrarModal();

        $this->dispatch('cerrar-modal-calificacion');
    }

    public function aplicarColorCalificacion($calificacion): string
    {
        if ($calificacion === null || $calificacion === '') {
            return 'bg-neutral-100 text-neutral-600 ring-neutral-200 dark:bg-neutral-700/40 dark:text-neutral-300 dark:ring-neutral-700';
        }

        if ($calificacion >= 9) {
            return 'bg-emerald-100 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20';
        }

        if ($calificacion >= 8) {
            return 'bg-blue-100 text-blue-700 ring-blue-200 dark:bg-blue-500/10 dark:text-blue-300 dark:ring-blue-500/20';
        }

        if ($calificacion >= 7) {
            return 'bg-amber-100 text-amber-700 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-500/20';
        }

        return 'bg-rose-100 text-rose-700 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:ring-rose-500/20';
    }

    public function textoBoton($calificacion): string
    {
        return $calificacion === null ? 'Capturar' : 'Editar';
    }

    public function render()
    {
        if (!$this->profesor_id) {
            return view('livewire.profesor.calificaciones', [
                'registros' => collect(),
                'total_registros' => 0,
                'promedio_general' => '0.00',
                'capturadas' => 0,
                'pendientes' => 0,
            ]);
        }

        $consulta = $this->consultaBase();

        if ($this->licenciatura_id) {
            $consulta->where('inscripciones.licenciatura_id', (int) $this->licenciatura_id);
        }

        if ($this->cuatrimestre_id) {
            $consulta->where('inscripciones.cuatrimestre_id', (int) $this->cuatrimestre_id);
        }

        if ($this->materia_id) {
            $consulta->where('materias.id', (int) $this->materia_id);
        }

        if ($this->generacion_id) {
            $consulta->where('inscripciones.generacion_id', (int) $this->generacion_id);
        }

        if ($this->buscar !== '') {
            $consulta->where(function ($query) {
                $query->where('alumnos.nombre', 'like', '%' . $this->buscar . '%')
                    ->orWhere('alumnos.apellido_paterno', 'like', '%' . $this->buscar . '%')
                    ->orWhere('alumnos.apellido_materno', 'like', '%' . $this->buscar . '%')
                    ->orWhere('datos_escolares.matricula', 'like', '%' . $this->buscar . '%')
                    ->orWhere('materias.nombre', 'like', '%' . $this->buscar . '%');
            });
        }

        $resumen = clone $consulta;

        $total_registros = (clone $resumen)->count();

        $promedio_general = (clone $resumen)
            ->whereNotNull('calificaciones.calificacion')
            ->avg('calificaciones.calificacion');

        $promedio_general = $promedio_general
            ? number_format((float) $promedio_general, 2)
            : '0.00';

        $capturadas = (clone $resumen)->whereNotNull('calificaciones.calificacion')->count();
        $pendientes = (clone $resumen)->whereNull('calificaciones.calificacion')->count();

        $registros = $consulta
            ->select(
                'inscripciones.id as inscripcion_id',
                'asignacion_materias.id as asignacion_materia_id',
                'calificaciones.id as calificacion_id',
                'calificaciones.calificacion',
                'calificaciones.fecha_captura',
                'materias.nombre as materia',
                'licenciaturas.nombre as licenciatura',
                'cuatrimestres.nombre_cuatrimestre as cuatrimestre',
                'generaciones.generacion',
                'alumnos.nombre as alumno_nombre',
                'alumnos.apellido_paterno as alumno_apellido_paterno',
                'alumnos.apellido_materno as alumno_apellido_materno',
                'datos_escolares.matricula'
            )
            ->orderBy('licenciaturas.nombre')
            ->orderBy('cuatrimestres.no_cuatrimestre')
            ->orderBy('generaciones.generacion')
            ->orderBy('materias.nombre')
            ->orderBy('alumnos.apellido_paterno')
            ->orderBy('alumnos.apellido_materno')
            ->orderBy('alumnos.nombre')
            ->paginate(10);

        return view('livewire.profesor.calificaciones', [
            'registros' => $registros,
            'total_registros' => $total_registros,
            'promedio_general' => $promedio_general,
            'capturadas' => $capturadas,
            'pendientes' => $pendientes,
        ]);
    }
}
