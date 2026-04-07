<?php

namespace App\Livewire\Admin\Matricula;

use App\Models\Alumno;
use App\Models\Cuatrimestre;
use App\Models\Generacion;
use App\Models\Inscripcion;
use App\Models\Licenciatura;
use Livewire\Attributes\On;
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
        'page' => ['except' => 1],
    ];

    public function mount(): void
    {
        // Carga los datos de los filtros.
        $this->licenciaturas = Licenciatura::query()->orderBy('id')->get(['id', 'nombre']);
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



    public function getRegistrosProperty()
    {
        $search = trim($this->search);

        return Inscripcion::query()
            ->with([
                'licenciatura:id,nombre',
                'generacion:id,generacion',
                'cuatrimestre:id,no_cuatrimestre,nombre_cuatrimestre',
                'alumno:id,user_id,curp,nombre,apellido_paterno,apellido_materno,fecha_nacimiento,sexo',
                'alumno.user:id,email,username',
                'alumno.datosEscolares:id,alumno_id,matricula,folio,foto',
                'alumno.documentacion:id,alumno_id,url_curp,url_acta_nacimiento,url_certificado_estudios',
            ])
            ->when($this->filtrar_licenciatura !== '', function ($query) {
                $query->where('licenciatura_id', (int) $this->filtrar_licenciatura);
            })
            ->when($this->filtrar_generacion !== '', function ($query) {
                $query->where('generacion_id', (int) $this->filtrar_generacion);
            })
            ->when($this->filtrar_cuatrimestre !== '', function ($query) {
                $query->where('cuatrimestre_id', (int) $this->filtrar_cuatrimestre);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('alumno', function ($alumnoQuery) use ($search) {
                        $alumnoQuery->where(function ($sub) use ($search) {
                            $sub->where('curp', 'like', "%{$search}%")
                                ->orWhere('nombre', 'like', "%{$search}%")
                                ->orWhere('apellido_paterno', 'like', "%{$search}%")
                                ->orWhere('apellido_materno', 'like', "%{$search}%");
                        });
                    })
                        ->orWhereHas('alumno.datosEscolares', function ($datosQuery) use ($search) {
                            $datosQuery->where('matricula', 'like', "%{$search}%")
                                ->orWhere('folio', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('licenciatura_id', 'asc')
            ->orderByDesc('id')
            ->paginate(10);
    }

    #[On('documentos-cargados')]
    public function render()
    {
        $registros = $this->registros;
        $coleccion = $registros->getCollection();

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

        $totalGeneral = $registros->total();

        return view('livewire.admin.matricula.mostrar-matricula', [
            'registros' => $registros,
            'statsPorLic' => $statsPorLic,
            'totalGeneral' => $totalGeneral,
        ]);
    }
}
