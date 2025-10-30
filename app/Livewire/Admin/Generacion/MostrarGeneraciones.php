<?php

namespace App\Livewire\Admin\Generacion;


use App\Models\Generacion;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;


class MostrarGeneraciones extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '';

    public $sortField = 'id';
    public $sortDirection = 'asc';

    public $erroresImportacion;

    public $archivo;




    public function getGeneracionesProperty()
    {
        return Generacion::where('generacion', 'like', '%' . $this->search . '%')
            ->orWhere(function ($query) {
            $query->where('status', "true")->whereRaw('? like ?', ['ACTIVA', '%' . $this->search . '%']);
            })
            ->orWhere(function ($query) {
            $query->where('status', "false")->whereRaw('? like ?', ['INACTIVA', '%' . $this->search . '%']);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }



    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function eliminarGeneracion($id)
    {
        $generacion = Generacion::find($id);

        if ($generacion) {
            $generacion->delete();

            $this->dispatch('swal', [
            'title' => 'Generación eliminada correctamente!',
            'icon' => 'success',
            'position' => 'top-end',
            ]);
        }
    }




    #[On('refreshGeneracion')]
    public function render()
    {
        return view('livewire.admin.generacion.mostrar-generaciones', [
            'generaciones' => $this->generaciones,
        ]);
    }
}
