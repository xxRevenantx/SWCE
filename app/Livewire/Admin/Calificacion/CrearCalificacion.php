<?php

namespace App\Livewire\Admin\Calificacion;

use App\Mail\CalificacionMail;
use App\Models\AsignacionMateria;
use App\Models\AsignarGeneracion;
use App\Models\Calificacion;
use App\Models\Cuatrimestre;
use App\Models\Generacion;
use App\Models\Inscripcion;
use App\Models\Licenciatura;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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

    /** Buscador */
    public string $busqueda = '';

    /** Columnas (materias asignadas) */
    public array $materias = [];

    /** Filas (alumnos) */
    public array $inscripciones = [];

    /** Matriz: [inscripcion_id][asignacion_materia_id] => calificacion */
    public array $calificaciones = [];

    /** Estado simple de UI */
    public bool $hayCambios = false;

    public function mount(): void
    {
        // Aquí cargo todas las licenciaturas.
        $this->licenciaturas = Licenciatura::query()
            ->orderBy('id')
            ->get();

        // Aquí dejo generaciones vacías al inicio.
        $this->generaciones = [];

        // Aquí cargo todos los cuatrimestres.
        $this->cuatrimestres = Cuatrimestre::query()
            ->orderBy('no_cuatrimestre')
            ->get();
    }

    /**
     * Aquí valido si se puede generar el PDF.
     */
    public function getPuedeGenerarPdfProperty(): bool
    {
        $filtrosCompletos = (bool) ($this->licenciatura_id && $this->generacion_id && $this->cuatrimestre_id);

        return $filtrosCompletos && !$this->hayCambios;
    }

    /**
     * Aquí genero la URL del PDF.
     */
    public function getPdfUrlProperty(): string
    {
        $filtrosCompletos = (bool) ($this->licenciatura_id && $this->generacion_id && $this->cuatrimestre_id);

        if (!$filtrosCompletos) {
            return '#';
        }

        return route('admin.pdf.calificaciones', [
            $this->licenciatura_id,
            $this->generacion_id,
            $this->cuatrimestre_id,
        ]);
    }

    /**
     * Aquí defino clases del botón PDF.
     */
    public function getClasePdfProperty(): string
    {
        $base = 'inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-400 to-indigo-500 text-white px-6 py-3 text-sm font-semibold shadow transition';

        return $this->puedeGenerarPdf
            ? $base . ' hover:opacity-95'
            : $base . ' pointer-events-none opacity-60 cursor-not-allowed';
    }

    /**
     * Aquí valido si se puede guardar.
     */
    public function getPuedeGuardarProperty(): bool
    {
        return $this->hayCambios && $this->getErrorBag()->isEmpty();
    }

    /**
     * Aquí defino clases del botón Guardar.
     */
    public function getClaseGuardarProperty(): string
    {
        $base = 'inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-sky-400 to-indigo-500 text-white px-6 py-3 text-sm font-semibold shadow transition';

        return $this->puedeGuardar
            ? $base . ' hover:opacity-95'
            : $base . ' opacity-60 cursor-not-allowed';
    }

    /** ======================= ENVÍOS ======================= */
    public function enviarCalificacion(int $inscripcionId): void
    {
        if ($inscripcionId <= 0) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'El alumno no tiene inscripción en este cuatrimestre.',
                'position' => 'top-end',
            ]);
            return;
        }

        $inscripcion = Inscripcion::with(['alumno.user', 'licenciatura'])
            ->where('id', $inscripcionId)
            ->first();

        if (!$inscripcion) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'No se encontró la inscripción.',
                'position' => 'top-end',
            ]);
            return;
        }

        if (
            (int) $inscripcion->licenciatura_id !== (int) $this->licenciatura_id ||
            (int) $inscripcion->generacion_id !== (int) $this->generacion_id
        ) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'La inscripción no coincide con la licenciatura y generación seleccionadas.',
                'position' => 'top-end',
            ]);
            return;
        }

        $correo = $inscripcion->alumno?->user?->email;

        if (empty($correo)) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'El alumno no tiene correo registrado.',
                'position' => 'top-end',
            ]);
            return;
        }

        $generacionObj = Generacion::find($this->generacion_id);
        $cuatrimestreObj = Cuatrimestre::find($this->cuatrimestre_id);
        $licenciatura = $inscripcion->licenciatura;

        $calificaciones = Calificacion::with(['asignacionMateria.materia', 'asignacionMateria.profesor'])
            ->where('inscripcion_id', $inscripcion->id)
            ->whereHas('asignacionMateria', function ($q) use ($inscripcion) {
                $q->where('cuatrimestre_id', $this->cuatrimestre_id)
                    ->where('licenciatura_id', $this->licenciatura_id);
            })
            ->get()
            ->sortBy(fn($item) => $item->asignacionMateria->materia->clave ?? '')
            ->values();

        $this->dispatch('swal', [
            'icon' => 'info',
            'title' => 'Enviando correo, espere…',
            'position' => 'top',
        ]);

        Mail::to($correo)->send(new CalificacionMail(
            $calificaciones,
            $inscripcion,
            $licenciatura,
            $generacionObj,
            $cuatrimestreObj
        ));

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Correo enviado correctamente.',
            'position' => 'top-end',
        ]);
    }

    public function updatedLicenciaturaId(): void
    {
        // Aquí limpio filtros dependientes.
        $this->generacion_id = null;
        $this->cuatrimestre_id = null;

        // Aquí limpio la tabla y estados.
        $this->generaciones = [];
        $this->materias = [];
        $this->inscripciones = [];
        $this->calificaciones = [];
        $this->hayCambios = false;
        $this->busqueda = '';

        if (!$this->licenciatura_id) {
            return;
        }

        // Aquí obtengo generaciones relacionadas con la licenciatura.
        $idsGeneracion = AsignarGeneracion::query()
            ->where('licenciatura_id', $this->licenciatura_id)
            ->pluck('generacion_id')
            ->unique()
            ->values();

        $this->generaciones = Generacion::query()
            ->whereIn('id', $idsGeneracion)
            ->orderBy('generacion')
            ->get();

        // Aquí mantengo todos los cuatrimestres disponibles.
        $this->cuatrimestres = Cuatrimestre::query()
            ->orderBy('no_cuatrimestre')
            ->get();
    }

    public function updatedGeneracionId(): void
    {
        $this->materias = [];
        $this->inscripciones = [];
        $this->calificaciones = [];
        $this->hayCambios = false;
        $this->busqueda = '';

        $this->cargarDatosSiListo();
    }

    public function updatedCuatrimestreId(): void
    {
        $this->materias = [];
        $this->inscripciones = [];
        $this->calificaciones = [];
        $this->hayCambios = false;
        $this->busqueda = '';

        $this->cargarDatosSiListo();
    }

    public function updatedBusqueda(): void
    {
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

        // Aquí cargo las materias del cuatrimestre seleccionado.
        $asignaciones = AsignacionMateria::query()
            ->where('asignacion_materias.licenciatura_id', $this->licenciatura_id)
            ->where('asignacion_materias.cuatrimestre_id', $this->cuatrimestre_id)
            ->join('materias', 'materias.id', '=', 'asignacion_materias.materia_id')
            ->where('materias.calificable', 'si')
            ->orderByRaw("COALESCE(materias.clave,'') ASC")
            ->select('asignacion_materias.*')
            ->with([
                'materia' => function ($q) {
                    $q->where('calificable', 'si');
                },
                'profesor',
            ])
            ->get();

        $this->materias = $asignaciones->map(function ($a) {
            $nombreMateria = $a->materia?->nombre ?? 'MATERIA';

            $profesor = $a->profesor
                ? trim(
                    ($a->profesor->nombre ?? '') . ' ' .
                        ($a->profesor->apellido_paterno ?? '') . ' ' .
                        ($a->profesor->apellido_materno ?? '')
                )
                : '—';

            return [
                'id' => (int) $a->id,
                'materia' => $nombreMateria,
                'profesor' => $profesor !== '' ? $profesor : '—',
            ];
        })->values()->toArray();

        $busqueda = trim($this->busqueda);

        // Aquí obtengo una inscripción base por alumno,
        // solo filtrando por licenciatura y generación.
        $ins = DB::table('inscripciones')
            ->join('alumnos', 'alumnos.id', '=', 'inscripciones.alumno_id')
            ->leftJoin('datos_escolares', 'datos_escolares.alumno_id', '=', 'alumnos.id')
            ->where('inscripciones.licenciatura_id', $this->licenciatura_id)
            ->where('inscripciones.generacion_id', $this->generacion_id)
            ->when($busqueda !== '', function ($q) use ($busqueda) {
                $q->where(function ($qq) use ($busqueda) {
                    $qq->where('datos_escolares.matricula', 'like', "%{$busqueda}%")
                        ->orWhere(
                            DB::raw("TRIM(CONCAT(alumnos.nombre,' ',IFNULL(alumnos.apellido_paterno,''),' ',IFNULL(alumnos.apellido_materno,'')))"),
                            'like',
                            "%{$busqueda}%"
                        );
                });
            })
            ->select(
                DB::raw('MIN(inscripciones.id) as inscripcion_id'),
                'alumnos.id as alumno_id',
                DB::raw("COALESCE(MAX(datos_escolares.matricula), '—') as matricula"),
                DB::raw("TRIM(CONCAT(MAX(alumnos.nombre),' ',IFNULL(MAX(alumnos.apellido_paterno),''),' ',IFNULL(MAX(alumnos.apellido_materno),''))) as alumno")
            )
            ->groupBy('alumnos.id')
            ->orderBy('alumno')
            ->get();

        $this->inscripciones = $ins->map(fn($r) => [
            'inscripcion_id' => (int) $r->inscripcion_id,
            'alumno_id' => (int) $r->alumno_id,
            'matricula' => $r->matricula ?: '—',
            'alumno' => $r->alumno ?: '—',
        ])->values()->toArray();

        // Aquí preparo la matriz vacía.
        $this->prepararCalificacionesEnBlanco();

        // Aquí cargo las calificaciones ya guardadas para las materias del cuatrimestre seleccionado.
        $this->cargarCalificacionesGuardadas();
    }

    private function prepararCalificacionesEnBlanco(): void
    {
        $this->calificaciones = [];

        foreach ($this->inscripciones as $fila) {
            $insId = (int) $fila['inscripcion_id'];

            foreach ($this->materias as $m) {
                $asigId = (int) $m['id'];
                $this->calificaciones[$insId][$asigId] = '';
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
        $this->cuatrimestres = Cuatrimestre::query()
            ->orderBy('no_cuatrimestre')
            ->get();

        $this->materias = [];
        $this->inscripciones = [];
        $this->calificaciones = [];
        $this->hayCambios = false;
        $this->busqueda = '';

        $this->resetErrorBag();
    }

    public function marcarCambio(): void
    {
        $this->hayCambios = true;
    }

    /**
     * Aquí valido cada celda cuando cambia una calificación.
     */
    public function updated($propiedad, $valor): void
    {
        if (!str_starts_with($propiedad, 'calificaciones.')) {
            return;
        }

        $partes = explode('.', $propiedad);
        if (count($partes) !== 3) {
            return;
        }

        if ($valor === '' || $valor === null) {
            $this->resetValidation($propiedad);
            $this->hayCambios = true;
            return;
        }

        $this->validateOnly(
            $propiedad,
            [
                $propiedad => 'nullable|numeric|min:0|max:10',
            ],
            [
                'numeric' => 'Debe ser un número.',
                'min' => 'Debe estar entre 0 y 10.',
                'max' => 'Debe estar entre 0 y 10.',
            ]
        );

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

            if ($insId <= 0) {
                continue;
            }

            foreach ($this->materias as $m) {
                $asigId = (int) $m['id'];
                $v = $this->calificaciones[$insId][$asigId] ?? null;

                if ($v === null || $v === '') {
                    continue;
                }

                if (is_numeric($v) && (float) $v >= 0 && (float) $v <= 10) {
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
        if ($inscripcionId <= 0) {
            return 0.0;
        }

        $suma = 0.0;
        $cont = 0;

        foreach ($this->materias as $m) {
            $asigId = (int) $m['id'];
            $v = $this->calificaciones[$inscripcionId][$asigId] ?? null;

            if ($v === null || $v === '' || !is_numeric($v)) {
                continue;
            }

            $v = (float) $v;

            if ($v < 0 || $v > 10) {
                continue;
            }

            $suma += $v;
            $cont++;
        }

        if ($cont === 0) {
            return 0.0;
        }

        $promedio = $suma / $cont;

        // Aquí corto el promedio a un decimal sin redondear.
        return floor($promedio * 10) / 10;
    }

    public function guardarCalificaciones(): void
    {
        if (!$this->hayCambios) {
            $this->dispatch('swal', [
                'icon' => 'info',
                'title' => 'No hay cambios por guardar.',
                'position' => 'top-end',
            ]);
            return;
        }

        if ($this->getErrorBag()->isNotEmpty()) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Hay calificaciones con error. Corrige los valores antes de guardar.',
                'position' => 'top-end',
            ]);
            return;
        }

        if (!DB::getSchemaBuilder()->hasTable('calificaciones')) {
            $this->addError('calificaciones', 'No existe la tabla calificaciones. Crea la tabla para guardar.');
            return;
        }

        DB::transaction(function () {
            foreach ($this->inscripciones as $fila) {
                $insId = (int) $fila['inscripcion_id'];

                // Aquí ignoro alumnos sin inscripción en el cuatrimestre seleccionado.
                if ($insId <= 0) {
                    continue;
                }

                foreach ($this->materias as $m) {
                    $asigId = (int) $m['id'];
                    $valor = $this->calificaciones[$insId][$asigId] ?? null;

                    if ($valor === null || $valor === '') {
                        DB::table('calificaciones')
                            ->where('inscripcion_id', $insId)
                            ->where('asignacion_materia_id', $asigId)
                            ->delete();
                        continue;
                    }

                    if (!is_numeric($valor)) {
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
