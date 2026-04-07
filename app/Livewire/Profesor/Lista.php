<?php

namespace App\Livewire\Profesor;

use App\Exports\ListaAlumnosProfesorExport;
use App\Exports\MateriasProfesorExport;
use App\Models\Cuatrimestre;
use App\Models\Generacion;
use App\Models\Licenciatura;
use App\Models\Profesor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Lista extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public Collection $licenciaturas;
    public Collection $cuatrimestres;
    public Collection $generaciones;

    public ?int $licenciatura_id = null;
    public ?int $cuatrimestre_id = null;
    public ?int $generacion_id = null;

    public string $search = '';

    public ?Profesor $profesor = null;
    public ?int $profesor_id = null;

    public function mount(): void
    {
        $this->profesor = Profesor::where('user_id', Auth::id())->first();
        $this->profesor_id = $this->profesor?->id;

        $this->licenciaturas = collect();
        $this->cuatrimestres = collect();
        $this->generaciones = collect();

        $this->cargarFiltros();
    }

    public function updatedLicenciaturaId($value): void
    {
        $this->licenciatura_id = !empty($value) ? (int) $value : null;
        $this->cuatrimestre_id = null;
        $this->generacion_id = null;

        $this->resetPage();
        $this->cargarFiltros();
    }

    public function updatedCuatrimestreId($value): void
    {
        $this->cuatrimestre_id = !empty($value) ? (int) $value : null;
        $this->generacion_id = null;

        $this->resetPage();
        $this->cargarFiltros();
    }

    public function updatedGeneracionId($value): void
    {
        $this->generacion_id = !empty($value) ? (int) $value : null;
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function limpiarFiltros(): void
    {
        $this->licenciatura_id = null;
        $this->cuatrimestre_id = null;
        $this->generacion_id = null;
        $this->search = '';

        $this->resetPage();
        $this->cargarFiltros();
    }

    public function getFiltrosCompletosProperty(): bool
    {
        return !empty($this->licenciatura_id)
            && !empty($this->cuatrimestre_id)
            && !empty($this->generacion_id);
    }

    public function baseConsultaHorarios()
    {
        return DB::table('horarios')
            ->join('asignacion_materias', 'horarios.asignacion_materia_id', '=', 'asignacion_materias.id')
            ->where('asignacion_materias.profesor_id', $this->profesor_id);
    }

    public function cargarFiltros(): void
    {
        if (!$this->profesor_id) {
            $this->licenciaturas = collect();
            $this->cuatrimestres = collect();
            $this->generaciones = collect();
            return;
        }

        $consultaLicenciaturas = $this->baseConsultaHorarios();

        $idsLicenciaturas = $consultaLicenciaturas
            ->distinct()
            ->pluck('horarios.licenciatura_id')
            ->filter()
            ->values();

        $this->licenciaturas = Licenciatura::whereIn('id', $idsLicenciaturas)
            ->orderBy('nombre')
            ->get();

        $consultaCuatrimestres = $this->baseConsultaHorarios();

        if ($this->licenciatura_id) {
            $consultaCuatrimestres->where('horarios.licenciatura_id', $this->licenciatura_id);
        }

        $idsCuatrimestres = $consultaCuatrimestres
            ->distinct()
            ->pluck('horarios.cuatrimestre_id')
            ->filter()
            ->values();

        $this->cuatrimestres = Cuatrimestre::whereIn('id', $idsCuatrimestres)
            ->orderBy('no_cuatrimestre')
            ->get();

        $consultaGeneraciones = $this->baseConsultaHorarios();

        if ($this->licenciatura_id) {
            $consultaGeneraciones->where('horarios.licenciatura_id', $this->licenciatura_id);
        }

        if ($this->cuatrimestre_id) {
            $consultaGeneraciones->where('horarios.cuatrimestre_id', $this->cuatrimestre_id);
        }

        $idsGeneraciones = $consultaGeneraciones
            ->distinct()
            ->pluck('horarios.generacion_id')
            ->filter()
            ->values();

        $this->generaciones = Generacion::whereIn('id', $idsGeneraciones)
            ->orderBy('generacion')
            ->get();
    }

    public function consultaMaterias()
    {
        if (!$this->profesor_id || !$this->filtrosCompletos) {
            return DB::table('materias')->whereRaw('1 = 0');
        }

        $consulta = DB::table('asignacion_materias')
            ->join('materias', 'asignacion_materias.materia_id', '=', 'materias.id')
            ->join('horarios', 'horarios.asignacion_materia_id', '=', 'asignacion_materias.id')
            ->leftJoin('licenciaturas', 'horarios.licenciatura_id', '=', 'licenciaturas.id')
            ->leftJoin('cuatrimestres', 'horarios.cuatrimestre_id', '=', 'cuatrimestres.id')
            ->leftJoin('generaciones', 'horarios.generacion_id', '=', 'generaciones.id')
            ->where('asignacion_materias.profesor_id', $this->profesor_id)
            ->where('horarios.licenciatura_id', $this->licenciatura_id)
            ->where('horarios.cuatrimestre_id', $this->cuatrimestre_id)
            ->where('horarios.generacion_id', $this->generacion_id);

        if (!empty($this->search)) {
            $search = trim($this->search);

            $consulta->where(function ($query) use ($search) {
                $query->where('materias.nombre', 'like', "%{$search}%")
                    ->orWhere('materias.clave', 'like', "%{$search}%");
            });
        }

        return $consulta
            ->select(
                'asignacion_materias.id as asignacion_materia_id',
                'materias.id as materia_id',
                'materias.clave',
                'materias.nombre as materia',
                'licenciaturas.nombre as licenciatura',
                'cuatrimestres.nombre_cuatrimestre as cuatrimestre',
                'generaciones.generacion as generacion'
            )
            ->groupBy(
                'asignacion_materias.id',
                'materias.id',
                'materias.clave',
                'materias.nombre',
                'licenciaturas.nombre',
                'cuatrimestres.nombre_cuatrimestre',
                'generaciones.generacion'
            )
            ->orderBy('materias.clave')
            ->orderBy('materias.nombre');
    }

    public function exportarExcelMateria(int $asignacionMateriaId)
    {
        if (
            !$this->profesor_id ||
            !$this->filtrosCompletos ||
            empty($asignacionMateriaId)
        ) {
            return;
        }

        $materiaSeleccionada = DB::table('asignacion_materias')
            ->join('materias', 'asignacion_materias.materia_id', '=', 'materias.id')
            ->join('horarios', 'horarios.asignacion_materia_id', '=', 'asignacion_materias.id')
            ->leftJoin('licenciaturas', 'horarios.licenciatura_id', '=', 'licenciaturas.id')
            ->leftJoin('cuatrimestres', 'horarios.cuatrimestre_id', '=', 'cuatrimestres.id')
            ->leftJoin('generaciones', 'horarios.generacion_id', '=', 'generaciones.id')
            ->where('asignacion_materias.id', $asignacionMateriaId)
            ->where('asignacion_materias.profesor_id', $this->profesor_id)
            ->where('horarios.licenciatura_id', $this->licenciatura_id)
            ->where('horarios.cuatrimestre_id', $this->cuatrimestre_id)
            ->where('horarios.generacion_id', $this->generacion_id)
            ->select(
                'asignacion_materias.id as asignacion_materia_id',
                'materias.id as materia_id',
                'materias.clave',
                'materias.nombre as materia',
                'licenciaturas.nombre as licenciatura',
                'cuatrimestres.nombre_cuatrimestre as cuatrimestre',
                'generaciones.generacion as generacion'
            )
            ->distinct()
            ->first();

        if (!$materiaSeleccionada) {
            return;
        }

        $search = trim($this->search);

        $alumnos = DB::table('inscripciones')
            ->join('alumnos', 'inscripciones.alumno_id', '=', 'alumnos.id')
            ->leftJoin('datos_escolares', 'alumnos.id', '=', 'datos_escolares.alumno_id')
            ->where('inscripciones.licenciatura_id', $this->licenciatura_id)
            ->where('inscripciones.status', 1)
            ->where('inscripciones.cuatrimestre_id', $this->cuatrimestre_id)
            ->where('inscripciones.generacion_id', $this->generacion_id)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subquery) use ($search) {
                    $subquery->where('datos_escolares.matricula', 'like', "%{$search}%")
                        ->orWhere('alumnos.nombre', 'like', "%{$search}%")
                        ->orWhere('alumnos.apellido_paterno', 'like', "%{$search}%")
                        ->orWhere('alumnos.apellido_materno', 'like', "%{$search}%")
                        ->orWhereRaw(
                            "CONCAT_WS(' ', alumnos.nombre, alumnos.apellido_paterno, alumnos.apellido_materno) LIKE ?",
                            ["%{$search}%"]
                        );
                });
            })
            ->select(
                'alumnos.id',
                'alumnos.nombre',
                'alumnos.apellido_paterno',
                'alumnos.apellido_materno',
                'datos_escolares.matricula'
            )
            ->distinct()
            ->orderBy('alumnos.apellido_paterno')
            ->orderBy('alumnos.apellido_materno')
            ->orderBy('alumnos.nombre')
            ->get();

        $nombreMateria = $materiaSeleccionada->materia ?? 'materia';
        $claveMateria = $materiaSeleccionada->clave ?? 'sin_clave';

        $nombreArchivo = 'lista_alumnos_' .
            str($claveMateria)->slug('_') . '_' .
            str($nombreMateria)->slug('_') . '_' .
            now()->format('Ymd_His') . '.xlsx';

        $nombreProfesor = trim(
            ($this->profesor->nombre ?? '') . ' ' .
            ($this->profesor->apellido_paterno ?? '') . ' ' .
            ($this->profesor->apellido_materno ?? '')
        );

        return Excel::download(
            new \App\Exports\ListaAlumnosProfesorExport(
                $alumnos,
                $nombreProfesor,
                $materiaSeleccionada->materia ?? 'Sin materia',
                $materiaSeleccionada->clave ?? 'Sin clave',
                $materiaSeleccionada->licenciatura ?? 'Sin licenciatura',
                $materiaSeleccionada->cuatrimestre ?? 'Sin cuatrimestre',
                $materiaSeleccionada->generacion ?? 'Sin generación',
            ),
            $nombreArchivo
        );
    }

    public function getPdfMateriaUrl(int $asignacionMateriaId): string
    {
        return route('profesor.pdf.lista-alumnos', [
            'profesor' => $this->profesor_id,
            'licenciatura' => $this->licenciatura_id,
            'cuatrimestre' => $this->cuatrimestre_id,
            'generacion' => $this->generacion_id,
            'asignacion_materia' => $asignacionMateriaId,
        ]);
    }

    public function render()
    {
        $materias = $this->consultaMaterias()->paginate(10);

        return view('livewire.profesor.lista', [
            'materias' => $materias,
        ]);
    }
}
