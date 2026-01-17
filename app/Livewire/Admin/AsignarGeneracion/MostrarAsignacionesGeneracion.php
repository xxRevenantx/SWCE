<?php

namespace App\Livewire\Admin\AsignarGeneracion;

use App\Models\AsignarGeneracion;
use App\Models\Generacion;
use App\Models\Licenciatura;
use Livewire\Component;
use Livewire\WithPagination;

class MostrarAsignacionesGeneracion extends Component
{

    use WithPagination;
    public $search = '';
    public $archivo;

    public $filtrar_licenciatura = '';
    public $filtrar_generacion = '';
    public $filtrar_modalidad = '';
    public $filtrar_activa = '';


    public $sortField = 'order';
    public $sortDirection = 'asc';

    public $erroresImportacion;


    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function getAsignacionesProperty()
    {
        $query = AsignarGeneracion::with(['licenciatura', 'modalidad', 'generacion']);

        if ($this->filtrar_licenciatura) {
            $query->where('licenciatura_id', $this->filtrar_licenciatura);
            $this->search = '';
        }

        if ($this->filtrar_generacion) {
            $query->where('generacion_id', $this->filtrar_generacion);
            $this->search = '';
        }

        if ($this->filtrar_modalidad) {
            $query->where('modalidad_id', $this->filtrar_modalidad);
            $this->search = '';
        }

        if ($this->filtrar_activa !== '') {
            $query->whereHas('generacion', function ($q) {
                $q->where('activa', $this->filtrar_activa === 'true' ? "true" : "false");
            });
            $this->search = '';
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('licenciatura', function ($query) {
                    $query->where('nombre', 'like', '%' . $this->search . '%');
                })
                    ->orWhereHas('generacion', function ($query) {
                        $query->where('generacion', 'like', '%' . $this->search . '%');
                    });
            });
        }

        return $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }



    public function limpiarFiltros()
    {
        $this->resetFilters();
        $this->search = '';
        $this->filtrar_generacion = '';
        $this->resetPage();
    }




    // Este método se ejecuta cuando se cambia el valor del campo de búsqueda
    public function updatedSearch()
    {
        $this->resetFilters();
        $this->resetPage();
    }

    // Estos métodos se ejecutan cuando cambian los filtros
    public function updatedFiltrarLicenciatura()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function updatedFiltrarGeneracion()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function updatedFiltrarModalidad()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function updatedFiltrarActiva()
    {
        $this->search = '';
        $this->resetPage();
    }

    // Este método reinicia todos los filtros
    protected function resetFilters()
    {
        $this->filtrar_licenciatura = '';
        $this->filtrar_generacion = '';
        $this->filtrar_modalidad = '';
        $this->filtrar_activa = '';
    }


    public function updating($property)
    {
        if (in_array($property, ['filtrar_licenciatura', 'filtrar_generacion', 'filtrar_activa', 'search'])) {
            $this->resetPage();
        }
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function eliminarAsignacion($id)
    {
        $asignacion = AsignarGeneracion::find($id);

        if ($asignacion) {
            $asignacion->delete();

            $this->dispatch('swal', [
                'title' => 'Asignación eliminada correctamente',
                'icon' => 'success',
                'position' => 'top-end',
            ]);
        }
    }



    public function render()
    {
        $licenciaturas = Licenciatura::all();
        $generaciones = Generacion::all();

        return view('livewire.admin.asignar-generacion.mostrar-asignaciones-generacion', ['asignaciones' => $this->asignaciones, 'licenciaturas' => $licenciaturas, 'generaciones' => $generaciones]);
    }
}
