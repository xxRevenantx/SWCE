<?php

namespace App\Livewire\Admin\Matricula;

use App\Models\Cuatrimestre;
use App\Models\Generacion;
use App\Models\Inscripcion;   // ✅ Aquí uso el modelo Inscripcion porque es el que conecta alumno + licenciatura + generación + cuatrimestre.
use App\Models\Licenciatura;
use Livewire\Component;
use Livewire\WithPagination;

class MostrarMatricula extends Component
{
    use WithPagination;

    public string $search = '';

    // Aquí guardo los filtros como string porque Livewire siempre los trae del URL como texto.
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
    ];


    public function mount(): void
    {
        // Aquí cargo los catálogos para que mis filtros tengan opciones disponibles desde que abre la vista.
        $this->licenciaturas = Licenciatura::query()->orderBy('nombre')->get(['id', 'nombre']);
        $this->generaciones = Generacion::query()->orderBy('generacion')->get(['id', 'generacion']);
        $this->cuatrimestres = Cuatrimestre::query()
            ->orderBy('no_cuatrimestre')
            ->get(['id', 'no_cuatrimestre', 'nombre_cuatrimestre']);
    }

    public function updatingSearch(): void
    {
        // Aquí reinicio la paginación para que al buscar, siempre empiece desde la página 1.
        $this->resetPage();
    }

    public function updatingFiltrarLicenciatura(): void
    {
        // Aquí reinicio la paginación cuando cambio el filtro de licenciatura.
        $this->resetPage();
    }

    public function updatingFiltrarGeneracion(): void
    {
        // Aquí reinicio la paginación cuando cambio el filtro de generación.
        $this->resetPage();
    }

    public function updatingFiltrarCuatrimestre(): void
    {
        // Aquí reinicio la paginación cuando cambio el filtro de cuatrimestre.
        $this->resetPage();
    }

    public function limpiarFiltros(): void
    {
        // Aquí limpio todo y dejo los filtros como string vacío para que no truene el tipado.
        $this->reset(['search', 'filtrar_licenciatura', 'filtrar_generacion', 'filtrar_cuatrimestre']);
        $this->filtrar_licenciatura = '';
        $this->filtrar_generacion = '';
        $this->filtrar_cuatrimestre = '';
        $this->resetPage();
    }


    public function exportarPdf(): void
    {
        // Aquí dejo preparado el punto donde después voy a conectar la exportación a PDF.
        $this->dispatch('toast', type: 'info', message: 'Aquí va tu exportación PDF (pendiente de conectar).');
    }

    public function getRegistrosProperty()
    {
        // Aquí limpio el texto para evitar que los espacios me afecten en la búsqueda.
        $search = trim($this->search);

        return Inscripcion::query()
            ->select('inscripciones.*')

            // Aquí hago join con licenciaturas para ordenar por su nombre y poder agrupar visualmente.
            ->join('licenciaturas', 'licenciaturas.id', '=', 'inscripciones.licenciatura_id')

            // Aquí hago join con alumnos porque la búsqueda incluye nombre y CURP.
            ->join('alumnos', 'alumnos.id', '=', 'inscripciones.alumno_id')

            // Aquí uso leftJoin con datos_escolares porque puede que aún no exista ese registro.
            ->leftJoin('datos_escolares', 'datos_escolares.alumno_id', '=', 'alumnos.id')

            // Aquí cargo relaciones para evitar consultas extra en la vista.
            ->with([
                'licenciatura:id,nombre',
                'generacion:id,generacion',
                'cuatrimestre:id,no_cuatrimestre,nombre_cuatrimestre',
                'alumno:id,user_id,curp,nombre,apellido_paterno,apellido_materno,fecha_nacimiento,sexo',
                'alumno.user:id,email,username', // ✅ NUEVO (email/username)
                'alumno.datosEscolares:id,alumno_id,matricula,folio,foto',
            ])

            // Aquí aplico los filtros si el usuario los selecciona.
            ->when($this->filtrar_licenciatura !== '', fn($q) => $q->where('inscripciones.licenciatura_id', (int) $this->filtrar_licenciatura))
            ->when($this->filtrar_generacion !== '', fn($q) => $q->where('inscripciones.generacion_id', (int) $this->filtrar_generacion))
            ->when($this->filtrar_cuatrimestre !== '', fn($q) => $q->where('inscripciones.cuatrimestre_id', (int) $this->filtrar_cuatrimestre))


            // Aquí hago la búsqueda por matrícula/folio (datos_escolares) y por CURP/nombres (alumnos).
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

            // Aquí ordeno por licenciatura para que se vea agrupado en la tabla.
            ->orderBy('licenciaturas.nombre', 'asc')

            // Aquí ordeno por la inscripción más reciente dentro de cada licenciatura.
            ->orderByDesc('inscripciones.id')

            // Aquí pagino el resultado.
            ->paginate(10);
    }


    public function render()
    {
        // Aquí obtengo la paginación ya lista.
        $registros = $this->registros;

        // Aquí tomo solo los registros de la página actual (lo que se está viendo).
        $coleccion = $registros->getCollection();

        // Aquí calculo estadísticas por licenciatura (solo para la página actual).
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

        // Aquí tomo el total general de todos los registros filtrados (considerando toda la paginación).
        $totalGeneral = $registros->total();

        return view('livewire.admin.matricula.mostrar-matricula', [
            'registros' => $registros,
            'statsPorLic' => $statsPorLic,
            'totalGeneral' => $totalGeneral,
        ]);
    }
}
