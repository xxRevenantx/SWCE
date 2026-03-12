<?php

namespace App\Livewire\Estudiante;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Calificaciones extends Component
{
    use WithPagination;

    // Búsqueda general.
    public string $buscar = '';

    // Datos del estudiante.
    public string $nombre_estudiante = 'Sin estudiante';
    public string $matricula = 'Sin matrícula';
    public string $licenciatura = 'Sin licenciatura';

    // Resumen general.
    public string $promedio_general = '0.00';
    public int $total_materias = 0;
    public int $materias_aprobadas = 0;
    public int $materias_reprobadas = 0;
    public int $materias_pendientes = 0;
    public int $total_cuatrimestres = 0;

    // Identificadores.
    public ?int $alumno_id = null;

    // Estructura agrupada por cuatrimestre.
    public array $cuatrimestres = [];

    // Cantidad de bloques por página.
    public int $porPagina = 3;

    protected string $paginationTheme = 'tailwind';

    public function mount(): void
    {
        $this->cargarDatosGenerales();
        $this->cargarCalificacionesPorCuatrimestre();
        $this->calcularResumenGeneral();
    }

    /**
     * Cuando cambia la búsqueda, regreso a la primera página.
     */
    public function updatingBuscar(): void
    {
        $this->resetPage();
    }

    /**
     * Carga la información principal del alumno autenticado.
     */
    public function cargarDatosGenerales(): void
    {
        $usuario = Auth::user();

        if (! $usuario) {
            return;
        }

        $registro = DB::table('alumnos')
            ->leftJoin('datos_escolares', 'datos_escolares.alumno_id', '=', 'alumnos.id')
            ->leftJoin('inscripciones', 'inscripciones.alumno_id', '=', 'alumnos.id')
            ->leftJoin('licenciaturas', 'licenciaturas.id', '=', 'inscripciones.licenciatura_id')
            ->where('alumnos.user_id', $usuario->id)
            ->select(
                'alumnos.id as alumno_id',
                'alumnos.nombre',
                'alumnos.apellido_paterno',
                'alumnos.apellido_materno',
                'datos_escolares.matricula',
                'licenciaturas.nombre as licenciatura'
            )
            ->first();

        if (! $registro) {
            return;
        }

        $this->alumno_id = $registro->alumno_id;

        $this->nombre_estudiante = trim(
            collect([
                $registro->nombre,
                $registro->apellido_paterno,
                $registro->apellido_materno,
            ])->filter()->implode(' ')
        );

        $this->matricula = $registro->matricula ?: 'Sin matrícula';
        $this->licenciatura = $registro->licenciatura ?: 'Sin licenciatura';
    }

    /**
     * Carga únicamente los cuatrimestres que sí tienen calificaciones capturadas.
     */
    public function cargarCalificacionesPorCuatrimestre(): void
    {
        if (! $this->alumno_id) {
            $this->cuatrimestres = [];
            return;
        }

        $filas = DB::table('calificaciones')
            ->join('inscripciones', 'inscripciones.id', '=', 'calificaciones.inscripcion_id')
            ->join('alumnos', 'alumnos.id', '=', 'inscripciones.alumno_id')
            ->join('asignacion_materias', 'asignacion_materias.id', '=', 'calificaciones.asignacion_materia_id')
            ->join('materias', 'materias.id', '=', 'asignacion_materias.materia_id')
            ->join('cuatrimestres', 'cuatrimestres.id', '=', 'asignacion_materias.cuatrimestre_id')
            ->join('licenciaturas', 'licenciaturas.id', '=', 'asignacion_materias.licenciatura_id')
            ->leftJoin('generaciones', 'generaciones.id', '=', 'inscripciones.generacion_id')
            ->leftJoin('profesores', 'profesores.id', '=', 'asignacion_materias.profesor_id')
            ->where('alumnos.id', $this->alumno_id)
            ->orderByDesc('cuatrimestres.no_cuatrimestre')
            ->orderBy('materias.nombre')
            ->select(
                'inscripciones.id as inscripcion_id',
                'inscripciones.fecha_inscripcion',
                'inscripciones.status',
                'cuatrimestres.id as cuatrimestre_id',
                'cuatrimestres.no_cuatrimestre',
                'cuatrimestres.nombre_cuatrimestre',
                'licenciaturas.nombre as licenciatura',
                'generaciones.generacion',
                'materias.id as materia_id',
                'materias.clave',
                'materias.nombre as materia',
                'materias.creditos',
                'calificaciones.calificacion',
                'calificaciones.fecha_captura',
                DB::raw("
                    TRIM(
                        CONCAT(
                            COALESCE(profesores.nombre, ''),
                            ' ',
                            COALESCE(profesores.apellido_paterno, ''),
                            ' ',
                            COALESCE(profesores.apellido_materno, '')
                        )
                    ) as profesor
                ")
            )
            ->get();

        $agrupados = $filas->groupBy(function ($fila) {
            return $fila->cuatrimestre_id . '-' . $fila->inscripcion_id;
        });

        $this->cuatrimestres = $agrupados
            ->map(function ($grupo) {
                $primero = $grupo->first();

                $materias = collect($grupo)
                    ->map(function ($fila) {
                        $calificacion = is_null($fila->calificacion) ? null : (float) $fila->calificacion;

                        return [
                            'materia_id' => $fila->materia_id,
                            'clave' => $fila->clave ?: 'Sin clave',
                            'materia' => $fila->materia ?: 'Sin materia',
                            'profesor' => filled(trim((string) $fila->profesor))
                                ? trim((string) $fila->profesor)
                                : 'Sin profesor asignado',
                            'creditos' => (int) ($fila->creditos ?? 0),
                            'calificacion' => $calificacion,
                            'calificacion_texto' => is_null($calificacion)
                                ? '—'
                                : $this->formatearDecimal($calificacion, 2),
                            'fecha_captura' => $fila->fecha_captura ?: 'Sin captura',
                            'estado' => $this->resolverEstado($calificacion),
                            'avance' => is_null($calificacion)
                                ? 0
                                : max(0, min(100, $calificacion * 10)),
                        ];
                    })
                    ->values();

                $promedioCuatrimestreNumerico = $this->calcularPromedioNumerico($materias);

                return [
                    'inscripcion_id' => $primero->inscripcion_id,
                    'cuatrimestre_id' => $primero->cuatrimestre_id,
                    'no_cuatrimestre' => (int) $primero->no_cuatrimestre,
                    'nombre_cuatrimestre' => $primero->nombre_cuatrimestre ?: 'Sin cuatrimestre',
                    'licenciatura' => $primero->licenciatura ?: 'Sin licenciatura',
                    'generacion' => $primero->generacion ?: 'Sin generación',
                    'fecha_inscripcion' => $primero->fecha_inscripcion ?: 'Sin fecha',
                    'status' => (string) $primero->status,
                    'promedio_numerico' => $promedioCuatrimestreNumerico,
                    'promedio' => $this->formatearDecimal($promedioCuatrimestreNumerico, 2),
                    'total_materias' => $materias->count(),
                    'aprobadas' => $materias->where('estado', 'Aprobada')->count(),
                    'reprobadas' => $materias->where('estado', 'Reprobada')->count(),
                    'pendientes' => $materias->where('estado', 'Pendiente')->count(),
                    'materias' => $materias->toArray(),
                ];
            })
            ->sortByDesc('no_cuatrimestre')
            ->values()
            ->toArray();
    }

    /**
     * Calcula el resumen global.
     */
    public function calcularResumenGeneral(): void
    {
        $todasLasMaterias = collect($this->cuatrimestres)
            ->pluck('materias')
            ->flatten(1)
            ->values();

        $this->total_cuatrimestres = count($this->cuatrimestres);
        $this->total_materias = $todasLasMaterias->count();
        $this->materias_aprobadas = $todasLasMaterias->where('estado', 'Aprobada')->count();
        $this->materias_reprobadas = $todasLasMaterias->where('estado', 'Reprobada')->count();
        $this->materias_pendientes = $todasLasMaterias->where('estado', 'Pendiente')->count();

        // Aquí se calcula con base en los promedios de cada cuatrimestre.
        $this->promedio_general = $this->calcularPromedioGeneralDesdeCuatrimestres(
            collect($this->cuatrimestres)
        );
    }

    /**
     * Filtra por búsqueda.
     */
    public function getCuatrimestresFiltradosProperty(): array
    {
        if (trim($this->buscar) === '') {
            return $this->cuatrimestres;
        }

        $texto = mb_strtolower(trim($this->buscar));

        return collect($this->cuatrimestres)
            ->map(function ($cuatrimestre) use ($texto) {
                $materiasFiltradas = collect($cuatrimestre['materias'])
                    ->filter(function ($fila) use ($texto) {
                        return str_contains(mb_strtolower((string) $fila['clave']), $texto)
                            || str_contains(mb_strtolower((string) $fila['materia']), $texto)
                            || str_contains(mb_strtolower((string) $fila['profesor']), $texto)
                            || str_contains(mb_strtolower((string) $fila['estado']), $texto)
                            || str_contains(mb_strtolower((string) $fila['fecha_captura']), $texto);
                    })
                    ->values();

                $coincideCuatrimestre =
                    str_contains(mb_strtolower((string) $cuatrimestre['nombre_cuatrimestre']), $texto)
                    || str_contains(mb_strtolower((string) $cuatrimestre['generacion']), $texto)
                    || str_contains(mb_strtolower((string) $cuatrimestre['licenciatura']), $texto)
                    || str_contains((string) $cuatrimestre['no_cuatrimestre'], $texto);

                if ($coincideCuatrimestre || $materiasFiltradas->isNotEmpty()) {
                    $promedioCuatrimestreNumerico = $this->calcularPromedioNumerico($materiasFiltradas);

                    $cuatrimestre['materias'] = $materiasFiltradas->toArray();
                    $cuatrimestre['total_materias'] = $materiasFiltradas->count();
                    $cuatrimestre['aprobadas'] = $materiasFiltradas->where('estado', 'Aprobada')->count();
                    $cuatrimestre['reprobadas'] = $materiasFiltradas->where('estado', 'Reprobada')->count();
                    $cuatrimestre['pendientes'] = $materiasFiltradas->where('estado', 'Pendiente')->count();
                    $cuatrimestre['promedio_numerico'] = $promedioCuatrimestreNumerico;
                    $cuatrimestre['promedio'] = $this->formatearDecimal($promedioCuatrimestreNumerico, 2);

                    return $cuatrimestre;
                }

                return null;
            })
            ->filter()
            ->sortByDesc('no_cuatrimestre')
            ->values()
            ->toArray();
    }

    /**
     * Pagina el arreglo de cuatrimestres filtrados.
     */
    public function getCuatrimestresPaginadosProperty(): LengthAwarePaginator
    {
        $coleccion = collect($this->cuatrimestresFiltrados)->values();

        $paginaActual = $this->getPage();
        $total = $coleccion->count();

        $resultados = $coleccion
            ->slice(($paginaActual - 1) * $this->porPagina, $this->porPagina)
            ->values();

        return new LengthAwarePaginator(
            $resultados,
            $total,
            $this->porPagina,
            $paginaActual,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );
    }

    /**
     * Resuelve el estado visual.
     */
    public function resolverEstado(?float $calificacion): string
    {
        if (is_null($calificacion)) {
            return 'Pendiente';
        }

        return $calificacion >= 6 ? 'Aprobada' : 'Reprobada';
    }

    /**
     * Calcula el promedio numérico de un cuatrimestre.
     */
    public function calcularPromedioNumerico(Collection $filas): float
    {
        $calificaciones = $filas
            ->pluck('calificacion')
            ->filter(fn($valor) => ! is_null($valor))
            ->values();

        if ($calificaciones->isEmpty()) {
            return 0.0;
        }

        return (float) $calificaciones->avg();
    }

    /**
     * Calcula el promedio general usando los promedios de cada cuatrimestre.
     */
    public function calcularPromedioGeneralDesdeCuatrimestres(Collection $cuatrimestres): string
    {
        $promedios = $cuatrimestres
            ->pluck('promedio_numerico')
            ->filter(fn($valor) => ! is_null($valor))
            ->filter(fn($valor) => $valor > 0)
            ->values();

        if ($promedios->isEmpty()) {
            return '0.00';
        }

        $sumaPromedios = $promedios->sum();
        $promedioGeneral = $sumaPromedios / $promedios->count();

        return $this->formatearDecimal($promedioGeneral, 1);
    }

    /**
     * Formatea sin redondear.
     */
    public function formatearDecimal(float $numero, int $decimales = 2): string
    {
        $factor = 10 ** $decimales;
        $truncado = floor($numero * $factor) / $factor;

        return number_format($truncado, $decimales, '.', '');
    }

    public function render()
    {
        return view('livewire.estudiante.calificaciones', [
            'cuatrimestresPaginados' => $this->cuatrimestresPaginados,
        ]);
    }
}
