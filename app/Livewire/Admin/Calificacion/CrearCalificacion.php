<?php

namespace App\Livewire\Admin\Calificacion;

use App\Models\AsignacionMateria;
use App\Models\AsignarGeneracion;
use App\Models\Cuatrimestre;
use App\Models\Generacion;
use App\Models\Licenciatura;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CrearCalificacion extends Component
{
    /** Catálogos para los filtros */
    public $licenciaturas = [];
    public $generaciones = [];
    public $cuatrimestres = [];

    /** Valores seleccionados en los filtros */
    public ?int $licenciatura_id = null;
    public ?int $generacion_id = null;
    public ?int $cuatrimestre_id = null;

    /**
     * Materias como ARRAY (para evitar problemas de Collection/array en Livewire)
     * Cada elemento:
     *  - id (asignacion_materias.id)
     *  - materia (nombre)
     *  - profesor (nombre completo o '—')
     */
    public array $materias = [];

    /**
     * Filas (inscripciones) como ARRAY
     * Cada elemento:
     *  - inscripcion_id
     *  - alumno_id
     *  - matricula
     *  - alumno (nombre completo)
     */
    public array $inscripciones = [];

    /** Matriz: [inscripcion_id][asignacion_materia_id] => calificacion */
    public array $calificaciones = [];

    /** UI */
    public bool $hayCambios = false;

    public function mount(): void
    {
        $this->licenciaturas = Licenciatura::query()
            ->orderBy('nombre')
            ->get();

        $this->cuatrimestres = Cuatrimestre::query()
            ->orderBy('no_cuatrimestre')
            ->get();
    }

    public function updatedLicenciaturaId(): void
    {
        $this->generacion_id = null;
        $this->cuatrimestre_id = null;

        $this->generaciones = [];
        $this->materias = [];
        $this->inscripciones = [];
        $this->calificaciones = [];
        $this->hayCambios = false;

        if (!$this->licenciatura_id) {
            return;
        }

        $idsGeneracion = AsignarGeneracion::query()
            ->where('licenciatura_id', $this->licenciatura_id)
            ->pluck('generacion_id')
            ->unique()
            ->values();

        $this->generaciones = Generacion::query()
            ->whereIn('id', $idsGeneracion)
            ->orderBy('generacion')
            ->get();
    }

    public function updatedGeneracionId(): void
    {
        $this->cuatrimestre_id = null;

        $this->materias = [];
        $this->inscripciones = [];
        $this->calificaciones = [];
        $this->hayCambios = false;

        $this->cargarDatosSiListo();
    }

    public function updatedCuatrimestreId(): void
    {
        $this->materias = [];
        $this->inscripciones = [];
        $this->calificaciones = [];
        $this->hayCambios = false;

        $this->cargarDatosSiListo();
    }

    private function cargarDatosSiListo(): void
    {
        if (!$this->licenciatura_id || !$this->generacion_id || !$this->cuatrimestre_id) {
            return;
        }

        // =========================
        // Materias (igual que Horarios)
        // =========================
        $asignaciones = AsignacionMateria::query()
            ->with(['materia', 'profesor'])
            ->where('licenciatura_id', $this->licenciatura_id)
            ->where('cuatrimestre_id', $this->cuatrimestre_id)
            ->orderBy('id')
            ->get();

        $this->materias = $asignaciones->map(function ($a) {
            $nombreMateria = $a->materia?->nombre ?? 'MATERIA';
            $prof = $a->profesor
                ? trim(($a->profesor->nombre ?? '') . ' ' . ($a->profesor->apellido_paterno ?? '') . ' ' . ($a->profesor->apellido_materno ?? ''))
                : '—';

            return [
                'id' => (int) $a->id, // asignacion_materias.id
                'materia' => $nombreMateria,
                'profesor' => $prof ?: '—',
            ];
        })->values()->toArray();

        // =========================
        // Inscripciones + Matrícula real (datos_escolares)
        // =========================
        $ins = DB::table('inscripciones')
            ->join('alumnos', 'alumnos.id', '=', 'inscripciones.alumno_id')
            ->leftJoin('datos_escolares', 'datos_escolares.alumno_id', '=', 'alumnos.id')
            ->where('inscripciones.licenciatura_id', $this->licenciatura_id)
            ->where('inscripciones.generacion_id', $this->generacion_id)
            ->where('inscripciones.cuatrimestre_id', $this->cuatrimestre_id)
            ->select(
                'inscripciones.id as inscripcion_id',
                'alumnos.id as alumno_id',
                DB::raw("COALESCE(datos_escolares.matricula, '—') as matricula"),
                DB::raw("TRIM(CONCAT(alumnos.nombre,' ',IFNULL(alumnos.apellido_paterno,''),' ',IFNULL(alumnos.apellido_materno,''))) as alumno")
            )
            ->orderBy('alumno')
            ->get();

        $this->inscripciones = $ins->map(fn($r) => [
            'inscripcion_id' => (int) $r->inscripcion_id,
            'alumno_id' => (int) $r->alumno_id,
            'matricula' => $r->matricula ?: '—',
            'alumno' => $r->alumno ?: '—',
        ])->toArray();

        // Matriz en blanco
        $this->prepararCalificacionesEnBlanco();

        // Cargar guardadas si existe tabla calificaciones
        $this->cargarCalificacionesGuardadas();
    }

    private function prepararCalificacionesEnBlanco(): void
    {
        $this->calificaciones = [];

        foreach ($this->inscripciones as $fila) {
            $insId = (int) $fila['inscripcion_id'];

            foreach ($this->materias as $m) {
                $asigId = (int) $m['id'];
                $this->calificaciones[$insId][$asigId] = '0';
            }
        }
    }

    private function cargarCalificacionesGuardadas(): void
    {
        if (!DB::getSchemaBuilder()->hasTable('calificaciones')) {
            return;
        }

        $idsIns = array_map(fn($f) => (int) $f['inscripcion_id'], $this->inscripciones);
        $idsAsig = array_map(fn($m) => (int) $m['id'], $this->materias);

        if (empty($idsIns) || empty($idsAsig)) {
            return;
        }

        // Estructura esperada:
        // calificaciones: inscripcion_id, asignacion_materia_id, calificacion
        $guardadas = DB::table('calificaciones')
            ->whereIn('inscripcion_id', $idsIns)
            ->whereIn('asignacion_materia_id', $idsAsig)
            ->get();

        foreach ($guardadas as $g) {
            $insId = (int) $g->inscripcion_id;
            $asigId = (int) $g->asignacion_materia_id;

            if (isset($this->calificaciones[$insId][$asigId])) {
                $this->calificaciones[$insId][$asigId] = (string) $g->calificacion;
            }
        }
    }

    public function limpiarFiltros(): void
    {
        $this->licenciatura_id = null;
        $this->generacion_id = null;
        $this->cuatrimestre_id = null;

        $this->generaciones = [];
        $this->materias = [];
        $this->inscripciones = [];
        $this->calificaciones = [];
        $this->hayCambios = false;
    }

    public function marcarCambio(): void
    {
        $this->hayCambios = true;
    }

    public function getTotalCeldasProperty(): int
    {
        return count($this->inscripciones) * count($this->materias);
    }

    public function getCeldasCapturadasProperty(): int
    {
        $capturadas = 0;

        foreach ($this->inscripciones as $fila) {
            $insId = (int) $fila['inscripcion_id'];

            foreach ($this->materias as $m) {
                $asigId = (int) $m['id'];
                $v = $this->calificaciones[$insId][$asigId] ?? null;

                if ($v !== null && $v !== '' && is_numeric($v) && (float) $v >= 0 && (float) $v <= 10) {
                    $capturadas++;
                }
            }
        }

        return $capturadas;
    }

    public function getPorcentajeCapturaProperty(): float
    {
        $total = $this->totalCeldas;
        if ($total <= 0) {
            return 0.0;
        }

        return round(($this->celdasCapturadas / $total) * 100, 1);
    }

    public function promedioFila(int $inscripcionId): float
    {
        $suma = 0.0;
        $n = 0;

        foreach ($this->materias as $m) {
            $asigId = (int) $m['id'];
            $v = $this->calificaciones[$inscripcionId][$asigId] ?? null;

            if ($v !== null && $v !== '' && is_numeric($v)) {
                $v = (float) $v;
                if ($v >= 0 && $v <= 10) {
                    $suma += $v;
                    $n++;
                }
            }
        }

        return $n > 0 ? round($suma / $n, 1) : 0.0;
    }

    public function guardarCalificaciones(): void
    {
        if (!DB::getSchemaBuilder()->hasTable('calificaciones')) {
            $this->addError('calificaciones', 'No existe la tabla calificaciones. Crea la tabla para guardar.');
            return;
        }

        DB::transaction(function () {
            foreach ($this->inscripciones as $fila) {
                $insId = (int) $fila['inscripcion_id'];

                foreach ($this->materias as $m) {
                    $asigId = (int) $m['id'];
                    $valor = $this->calificaciones[$insId][$asigId] ?? null;

                    if ($valor === null || $valor === '' || !is_numeric($valor)) {
                        continue;
                    }

                    $valor = (float) $valor;
                    if ($valor < 0 || $valor > 10) {
                        continue;
                    }

                    DB::table('calificaciones')->updateOrInsert(
                        [
                            'inscripcion_id' => $insId,
                            'asignacion_materia_id' => $asigId,
                        ],
                        [
                            'calificacion' => $valor,
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );
                }
            }
        });

        $this->resetErrorBag();
        $this->hayCambios = false;

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Calificaciones guardadas',
            'position' => 'top-end',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.calificacion.crear-calificacion', [
            'licenciaturas' => $this->licenciaturas,
            'generaciones' => $this->generaciones,
            'cuatrimestres' => $this->cuatrimestres,
        ]);
    }
}
