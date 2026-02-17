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
    /** Listas para los selects */
    public $licenciaturas = [];
    public $generaciones = [];
    public $cuatrimestres = [];

    /** Filtros */
    public ?int $licenciatura_id = null;
    public ?int $generacion_id = null;
    public ?int $cuatrimestre_id = null;

    /** Columnas (materias asignadas) */
    public array $materias = [];

    /** Filas (inscripciones/alumnos) */
    public array $inscripciones = [];

    /** Matriz: [inscripcion_id][asignacion_materia_id] => calificacion */
    public array $calificaciones = [];

    /** Estado simple de UI */
    public bool $hayCambios = false;

    public function mount(): void
    {
        // Carga licenciaturas para el primer filtro
        $this->licenciaturas = Licenciatura::query()
            ->orderBy('id')
            ->get();

        // Al inicio no se muestran generaciones ni cuatrimestres
        $this->generaciones = [];
        $this->cuatrimestres = [];
    }

    public function updatedLicenciaturaId(): void
    {
        // Limpia filtros dependientes
        $this->generacion_id = null;
        $this->cuatrimestre_id = null;

        // Limpia datos de la tabla
        $this->generaciones = [];
        $this->cuatrimestres = [];
        $this->materias = [];
        $this->inscripciones = [];
        $this->calificaciones = [];
        $this->hayCambios = false;

        // Si no hay licenciatura, se queda vacío
        if (!$this->licenciatura_id) {
            return;
        }

        // Generaciones disponibles según asignar_generaciones
        $idsGeneracion = AsignarGeneracion::query()
            ->where('licenciatura_id', $this->licenciatura_id)
            ->pluck('generacion_id')
            ->unique()
            ->values();

        $this->generaciones = Generacion::query()
            ->whereIn('id', $idsGeneracion)
            ->orderBy('generacion')
            ->get();

        // Cuatrimestres disponibles según asignacion_materias
        $idsCuatrimestre = AsignacionMateria::query()
            ->where('licenciatura_id', $this->licenciatura_id)
            ->pluck('cuatrimestre_id')
            ->unique()
            ->values();

        $this->cuatrimestres = Cuatrimestre::query()
            ->whereIn('id', $idsCuatrimestre)
            ->orderBy('no_cuatrimestre')
            ->get();
    }

    public function updatedGeneracionId(): void
    {
        // Limpia datos dependientes
        $this->materias = [];
        $this->inscripciones = [];
        $this->calificaciones = [];
        $this->hayCambios = false;

        // Carga si ya están completos los filtros
        $this->cargarDatosSiListo();
    }

    public function updatedCuatrimestreId(): void
    {
        // Limpia datos dependientes
        $this->materias = [];
        $this->inscripciones = [];
        $this->calificaciones = [];
        $this->hayCambios = false;

        // Carga si ya están completos los filtros
        $this->cargarDatosSiListo();
    }

    private function cargarDatosSiListo(): void
    {
        // Se requieren los 3 filtros
        if (!$this->licenciatura_id || !$this->generacion_id || !$this->cuatrimestre_id) {
            return;
        }

        // 1) Materias asignadas a esa licenciatura y cuatrimestre
        $asignaciones = AsignacionMateria::query()
            ->with(['materia', 'profesor'])
            ->where('licenciatura_id', $this->licenciatura_id)
            ->where('cuatrimestre_id', $this->cuatrimestre_id)
            ->orderBy('id')
            ->get();

        $this->materias = $asignaciones->map(function ($a) {
            $nombreMateria = $a->materia?->nombre ?? 'MATERIA';

            // Nombre real del profesor (con tus columnas)
            $profesor = $a->profesor
                ? trim(
                    ($a->profesor->nombre ?? '') . ' ' .
                        ($a->profesor->apellido_paterno ?? '') . ' ' .
                        ($a->profesor->apellido_materno ?? '')
                )
                : '—';

            return [
                'id' => (int) $a->id,            // asignacion_materias.id
                'materia' => $nombreMateria,
                'profesor' => $profesor !== '' ? $profesor : '—',
            ];
        })->values()->toArray();

        // 2) Alumnos inscritos en esa licenciatura, generación y cuatrimestre
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

        // 3) Matriz vacía para capturar
        $this->prepararCalificacionesEnBlanco();

        // 4) Si existe la tabla, se cargan calificaciones guardadas
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
        // Si no existe tabla, se omite
        if (!DB::getSchemaBuilder()->hasTable('calificaciones')) {
            return;
        }

        $idsIns = array_map(fn($f) => (int) $f['inscripcion_id'], $this->inscripciones);
        $idsAsig = array_map(fn($m) => (int) $m['id'], $this->materias);

        if (empty($idsIns) || empty($idsAsig)) {
            return;
        }

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
        // Deja todo en cero
        $this->licenciatura_id = null;
        $this->generacion_id = null;
        $this->cuatrimestre_id = null;

        $this->generaciones = [];
        $this->cuatrimestres = [];
        $this->materias = [];
        $this->inscripciones = [];
        $this->calificaciones = [];
        $this->hayCambios = false;
    }

    public function marcarCambio(): void
    {
        // Habilita el botón de guardar
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
        // Si falta tabla, se muestra error
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

                    // Si está vacío, se ignora
                    if ($valor === null || $valor === '' || !is_numeric($valor)) {
                        continue;
                    }

                    $valor = (float) $valor;

                    // Solo 0 a 10
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
                            'fecha_captura' => now(),
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
