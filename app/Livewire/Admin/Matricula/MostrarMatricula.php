<?php

namespace App\Livewire\Admin\Matricula;

use App\Models\Cuatrimestre;
use App\Models\Generacion;
use App\Models\Inscripcion;   // ✅ AJUSTA si tu modelo se llama diferente (ej. Alumno, Matricula, etc.)
use App\Models\Licenciatura;
use Livewire\Component;
use Livewire\WithPagination;

class MostrarMatricula extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $filtrar_licenciatura = null;
    public ?int $filtrar_generacion = null;
    public ?int $filtrar_cuatrimestre = null;

    /** Catálogos */
    public $licenciaturas = [];
    public $generaciones = [];
    public $cuatrimestres = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'filtrar_licenciatura' => ['except' => null],
        'filtrar_generacion' => ['except' => null],
        'filtrar_cuatrimestre' => ['except' => null],
    ];

    public function mount(): void
    {
        // ✅ Ajusta los orderBy/columns si tus tablas cambian
        $this->licenciaturas = Licenciatura::query()->orderBy('nombre')->get(['id', 'nombre']);
        $this->generaciones = Generacion::query()->orderBy('generacion')->get(['id', 'generacion']);
        $this->cuatrimestres = Cuatrimestre::query()->orderBy('no_cuatrimestre')->get(['id', 'no_cuatrimestre', 'nombre_cuatrimestre']);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFiltrarLicenciatura(): void
    {
        $this->resetPage();
    }

    public function updatingFiltrarGeneracion(): void
    {
        $this->resetPage();
    }

    public function updatingFiltrarCuatrimestre(): void
    {
        $this->resetPage();
    }

    public function limpiarFiltros(): void
    {
        $this->reset(['search', 'filtrar_licenciatura', 'filtrar_generacion', 'filtrar_cuatrimestre']);
        $this->resetPage();
    }

    public function exportarPdf(): void
    {
        // ✅ Aquí conectas tu export (Dompdf / Snappy / etc.)
        // Ejemplo: return redirect()->route('admin.matricula.pdf', [...filtros...]);
        $this->dispatch('toast', type: 'info', message: 'Aquí va tu exportación PDF (pendiente de conectar).');
    }

    public function getRegistrosProperty()
    {
        $search = trim($this->search);

        // ✅ AJUSTA relaciones/campos según tu modelo real
        return Inscripcion::query()
            ->with([
                'alumno',          // -> nombre, apellidos, curp, sexo, foto...
                'licenciatura',
                'generacion',
                'cuatrimestre',
            ])
            ->when($this->filtrar_licenciatura, fn($q) => $q->where('licenciatura_id', $this->filtrar_licenciatura))
            ->when($this->filtrar_generacion, fn($q) => $q->where('generacion_id', $this->filtrar_generacion))
            ->when($this->filtrar_cuatrimestre, fn($q) => $q->where('cuatrimestre_id', $this->filtrar_cuatrimestre))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    // ✅ Ajusta campos reales
                    $qq->where('matricula', 'like', "%{$search}%")
                        ->orWhere('folio', 'like', "%{$search}%")
                        ->orWhereHas('alumno', function ($qa) use ($search) {
                            $qa->where('nombre', 'like', "%{$search}%")
                                ->orWhere('apellido_paterno', 'like', "%{$search}%")
                                ->orWhere('apellido_materno', 'like', "%{$search}%")
                                ->orWhere('curp', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.matricula.mostrar-matricula', [
            'registros' => $this->registros,
        ]);
    }
}
