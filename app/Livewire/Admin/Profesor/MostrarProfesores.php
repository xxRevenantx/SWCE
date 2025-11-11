<?php

namespace App\Livewire\Admin\Profesor;

use App\Models\Profesor;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MostrarProfesores extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '';



    public function getProfesoresProperty()
    {
        return Profesor::with('user')
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('apellido_paterno', 'like', '%' . $this->search . '%')
            ->orWhere('apellido_materno', 'like', '%' . $this->search . '%')
            ->orWhereHas('user', function ($query) {
                $query->where('email', 'like', '%' . $this->search . '%');
            })
            ->where('status', 'true')
            ->orderBy('id', 'desc')
            ->paginate(10);
    }



    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function eliminarProfesor($id)
    {
        $profesor = Profesor::find($id);

        if ($profesor) {
            $profesor->delete();

            $this->dispatch('swal', [
            'title' => '¡Profesor eliminado correctamente!',
            'icon' => 'success',
            'position' => 'top-end',
            ]);
        }
    }



     #[On('refreshProfesores')]
    public function render()
    {
        return view('livewire.admin.profesor.mostrar-profesores', [
            'profesores' => $this->profesores
        ]);
    }
}
