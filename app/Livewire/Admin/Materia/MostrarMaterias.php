<?php

namespace App\Livewire\Admin\Materia;

use App\Models\Cuatrimestre;
use App\Models\Licenciatura;
use App\Models\Materia;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MostrarMaterias extends Component
{
    use WithFileUploads;
    use WithPagination;

    public string $search = '';

    // Filtros (para tu UI)
    public ?int $filtrar_licenciatura = null;
    public ?int $filtrar_cuatrimestre = null;
    public string $filtrar_calificable = ''; // '', '1', '0'

    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    public $erroresImportacion;
    public $archivo;

    public function getMateriasProperty()
    {
        $search = trim($this->search);

        $allowedSorts = ['id', 'nombre', 'slug', 'clave', 'creditos', 'cuatrimestre_id', 'calificable', 'licenciatura_id'];
        $sortField = in_array($this->sortField, $allowedSorts, true) ? $this->sortField : 'id';
        $sortDirection = $this->sortDirection === 'desc' ? 'desc' : 'asc';

        return Materia::query()
            ->with(['cuatrimestre', 'licenciatura'])

            // ✅ Filtro: Licenciatura
            ->when($this->filtrar_licenciatura, function ($q) {
                $q->where('licenciatura_id', $this->filtrar_licenciatura);
            })

            // ✅ Filtro: Cuatrimestre
            ->when($this->filtrar_cuatrimestre, function ($q) {
                $q->where('cuatrimestre_id', $this->filtrar_cuatrimestre);
            })

            // ✅ Filtro: Calificable
            ->when($this->filtrar_calificable !== '', function ($q) {
                $q->where('calificable', $this->filtrar_calificable === '1');
            })

            // ✅ Búsqueda (agrupada)
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('nombre', 'like', "%{$search}%")
                        ->orWhere('clave', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");

                    $s = mb_strtolower($search);
                    if (in_array($s, ['si', 'sí', 's'], true)) {
                        $qq->orWhere('calificable', true);
                    } elseif (in_array($s, ['no', 'n'], true)) {
                        $qq->orWhere('calificable', false);
                    }
                });
            })

            // ✅ Agrupado por licenciatura
            ->orderBy('licenciatura_id', 'asc')
            ->orderBy('cuatrimestre_id', 'asc')

            // ✅ tu sort adicional
            ->orderBy($sortField, $sortDirection)

            ->paginate(10);
    }



    /**
     * ✅ Resetear paginación al cambiar búsqueda o filtros
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFiltrarLicenciatura()
    {
        $this->resetPage();
        $this->filtrar_cuatrimestre = null; // al cambiar licenciatura, limpiar cuatrimestre
    }

    public function updatingFiltrarCuatrimestre()
    {
        $this->resetPage();
    }
    public function updatingFiltrarCalificable()
    {
        $this->resetPage();
    }

    public function eliminarMateria($id)
    {
        $materia = Materia::find($id);

        if ($materia) {
            $materia->delete();

            $this->dispatch('swal', [
                'title' => 'Materia eliminada correctamente!',
                'icon' => 'success',
                'position' => 'top-end',
            ]);
        }
    }

    #[On('refreshMaterias')]
    public function render()
    {
        $licenciaturas = Licenciatura::orderBy('id', 'desc')->get();

        $cuatrimestres = Cuatrimestre::orderBy('id', 'desc')->get();

        return view('livewire.admin.materia.mostrar-materias', [
            'materias' => $this->materias,
            'licenciaturas' => $licenciaturas,
            'cuatrimestres' => $cuatrimestres,
        ]);
    }
}
