<?php

namespace App\Livewire\Admin\Matricula;

use App\Models\Alumno;
use App\Models\Cuatrimestre;
use App\Models\Generacion;
use App\Models\Inscripcion;
use App\Models\Licenciatura;
use Livewire\Component;
use Livewire\WithPagination;

class MostrarMatricula extends Component
{
    use WithPagination;

    public string $search = '';

    // Los filtros se manejan como texto para evitar problemas con Livewire.
    public string $filtrar_licenciatura = '';
    public string $filtrar_generacion = '';
    public string $filtrar_cuatrimestre = '';

    /** Catálogos */
    public $licenciaturas = [];
    public $generaciones = [];
    public $cuatrimestres = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'filtrar_licenciatura' => ['except' => ''],
        'filtrar_generacion' => ['except' => ''],
        'filtrar_cuatrimestre' => ['except' => ''],
        'page' => ['except' => 1], // Mantiene la página actual en la URL
    ];

    public function mount(): void
    {
        // Carga los datos de los filtros.
        $this->licenciaturas = Licenciatura::query()->orderBy('nombre')->get(['id', 'nombre']);
        $this->generaciones = Generacion::query()->orderBy('generacion')->get(['id', 'generacion']);
        $this->cuatrimestres = Cuatrimestre::query()
            ->orderBy('no_cuatrimestre')
            ->get(['id', 'no_cuatrimestre', 'nombre_cuatrimestre']);
    }

    public function updatingSearch(): void
    {
        // Regresa a la primera página al buscar.
        $this->resetPage();
    }

    public function updatingFiltrarLicenciatura(): void
    {
        // Regresa a la primera página al cambiar el filtro.
        $this->resetPage();
    }

    public function updatingFiltrarGeneracion(): void
    {
        // Regresa a la primera página al cambiar el filtro.
        $this->resetPage();
    }

    public function updatingFiltrarCuatrimestre(): void
    {
        // Regresa a la primera página al cambiar el filtro.
        $this->resetPage();
    }

    public function eliminarAlumno(int $id): void
    {
        $paginaActual = $this->getPage();

        $alumno = Alumno::findOrFail($id);

        if (!$alumno) {
            $this->dispatch('swal', [
                'position' => 'top',
                'title' => 'Error: Alumno no encontrado',
                'icon' => 'error',
            ]);
            return;
        }

        $alumno->delete();

        $this->dispatch('swal', [
            'position' => 'top',
            'title' => 'Alumno Eliminado',
            'icon' => 'success',
        ]);

        // Mantiene la página actual o retrocede si quedó vacía.
        $registrosEnPagina = $this->registros;

        if ($registrosEnPagina->count() === 0 && $registrosEnPagina->total() > 0 && $paginaActual > 1) {
            $this->setPage($paginaActual - 1);
        } else {
            $this->setPage($paginaActual);
        }
    }

    public function limpiarFiltros(): void
    {
        // Limpia búsqueda y filtros.
        $this->reset(['search', 'filtrar_licenciatura', 'filtrar_generacion', 'filtrar_cuatrimestre']);
        $this->filtrar_licenciatura = '';
        $this->filtrar_generacion = '';
        $this->filtrar_cuatrimestre = '';
        $this->resetPage();
    }

    public function exportarPdf(): void
    {
        // Punto pendiente para conectar la exportación.
        $this->dispatch('toast', type: 'info', message: 'Aquí va tu exportación PDF (pendiente de conectar).');
    }

    public function getRegistrosProperty()
    {
        // Quita espacios al inicio y al final.
        $search = trim($this->search);

        return Inscripcion::query()
            ->select('inscripciones.*')

            // Se une con licenciaturas para ordenar por nombre.
            ->join('licenciaturas', 'licenciaturas.id', '=', 'inscripciones.licenciatura_id')

            // Se une con alumnos para buscar por nombre y CURP.
            ->join('alumnos', 'alumnos.id', '=', 'inscripciones.alumno_id')

            // Excluye alumnos eliminados.
            ->whereNull('alumnos.deleted_at')

            // Puede no existir información escolar.
            ->leftJoin('datos_escolares', 'datos_escolares.alumno_id', '=', 'alumnos.id')

            // Carga relaciones para evitar consultas extra.
            ->with([
                'licenciatura:id,nombre',
                'generacion:id,generacion',
                'cuatrimestre:id,no_cuatrimestre,nombre_cuatrimestre',
                'alumno:id,user_id,curp,nombre,apellido_paterno,apellido_materno,fecha_nacimiento,sexo',
                'alumno.user:id,email,username',
                'alumno.datosEscolares:id,alumno_id,matricula,folio,foto',
            ])

            // Aplica filtros.
            ->when($this->filtrar_licenciatura !== '', fn($q) => $q->where('inscripciones.licenciatura_id', (int) $this->filtrar_licenciatura))
            ->when($this->filtrar_generacion !== '', fn($q) => $q->where('inscripciones.generacion_id', (int) $this->filtrar_generacion))
            ->when($this->filtrar_cuatrimestre !== '', fn($q) => $q->where('inscripciones.cuatrimestre_id', (int) $this->filtrar_cuatrimestre))

            // Busca por matrícula, folio, CURP y nombres.
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('datos_escolares.matricula', 'like', "%{$search}%")
                        ->orWhere('datos_escolares.folio', 'like', "%{$search}%")
                        ->orWhere('alumnos.curp', 'like', "%{$search}%")
                        ->orWhere('alumnos.nombre', 'like', "%{$search}%")
                        ->orWhere('alumnos.apellido_paterno', 'like', "%{$search}%")
                        ->orWhere('alumnos.apellido_materno', 'like', "%{$search}%");
                });
            })

            // Ordena por licenciatura.
            ->orderBy('licenciaturas.nombre', 'asc')

            // Dentro de cada licenciatura muestra primero los más recientes.
            ->orderByDesc('inscripciones.id')

            // Pagina los resultados.
            ->paginate(10);
    }

    public function render()
    {
        // Obtiene los registros paginados.
        $registros = $this->registros;

        // Toma solo los registros de la página actual.
        $coleccion = $registros->getCollection();

        // Calcula datos por licenciatura en la página actual.
        $statsPorLic = $coleccion
            ->groupBy(fn($r) => $r->licenciatura_id ?? 0)
            ->map(function ($items) {
                $hombres = $items->filter(fn($r) => strtoupper($r->alumno?->sexo ?? '') === 'M')->count();
                $mujeres = $items->filter(fn($r) => strtoupper($r->alumno?->sexo ?? '') === 'F')->count();

                $bajas = $items->filter(fn($r) => !(bool) ($r->status ?? true))->count();
                $activos = $items->count() - $bajas;

                return [
                    'hombres' => $hombres,
                    'mujeres' => $mujeres,
                    'activos' => $activos,
                    'bajas' => $bajas,
                    'total' => $items->count(),
                ];
            })
            ->toArray();

        // Total general de los registros filtrados.
        $totalGeneral = $registros->total();

        return view('livewire.admin.matricula.mostrar-matricula', [
            'registros' => $registros,
            'statsPorLic' => $statsPorLic,
            'totalGeneral' => $totalGeneral,
        ]);
    }
}
