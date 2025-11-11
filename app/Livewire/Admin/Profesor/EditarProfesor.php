<?php

namespace App\Livewire\Admin\Profesor;

use App\Models\Profesor;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditarProfesor extends Component
{
    use WithFileUploads;

    public $user_id;
    public $open = false;
    public $profesorId;
    public $nombre;
    public $apellido_paterno;
    public $apellido_materno;
    public $CURP;
    public $telefono;
    public $perfil;
    public $color;

    /** Foto actual (string) y nueva (UploadedFile) */
    public $foto;        // ej. "abc.jpg" guardada en BD
    public $foto_nueva;  // UploadedFile temporal

    public $status;

    #[On('editarModal')]
    public function editarModal($id)
    {
        $profesor = Profesor::findOrFail($id);

        $this->profesorId       = $profesor->id;
        $this->user_id          = $profesor->user_id;
        $this->nombre           = $profesor->nombre;
        $this->apellido_paterno = $profesor->apellido_paterno;
        $this->apellido_materno = $profesor->apellido_materno;
        $this->CURP             = $profesor->CURP;
        $this->telefono         = $profesor->telefono;
        $this->perfil           = $profesor->perfil;
        $this->color            = $profesor->color;
        $this->foto             = $profesor->foto;   // nombre de archivo actual
        $this->foto_nueva       = null;              // limpiar temporal siempre
        $this->status           = $profesor->status == "true";

        $this->open = true;

        $this->dispatch('editar-cargado');
    }

    public function actualizarProfesor()
    {
        $this->validate([
            'nombre'        => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'telefono'      => 'nullable|string|max:10',
            'perfil'        => 'nullable|string|max:500',
            'color'         => 'nullable|string|max:7',
            'CURP'          => 'required|string|max:18',
            'status'        => 'required',
            'foto_nueva'    => 'nullable|image|max:2048|mimes:jpeg,jpg,png',
        ],[
            'nombre.required' => 'El campo nombre es obligatorio.',
            'apellido_paterno.required' => 'El campo apellido paterno es obligatorio.',
            'foto_nueva.image' => 'El archivo debe ser una imagen.',
            'foto_nueva.max' => 'La imagen no puede pesar más de 2MB.',
        ]);

        // Normaliza status a string "true"/"false" como en la BD
        $statusStr = $this->status ? "true" : "false";

        // Manejo de imagen (disk 'public')
        if ($this->foto_nueva) {
            // Borra la anterior si existe
            if ($this->foto) {
                Storage::disk('public')->delete('profesores/' . $this->foto);
            }
            // Guarda nueva y conserva solo el basename
            $path = $this->foto_nueva->store('profesores', 'public');
            $this->foto = basename($path);
        }

        $profesor = Profesor::find($this->profesorId);

        if ($profesor) {
            $profesor->update([
                'user_id'          => $this->user_id,
                'nombre'           => trim($this->nombre),
                'apellido_paterno' => trim($this->apellido_paterno),
                'apellido_materno' => trim($this->apellido_materno),
                'CURP'             => trim($this->CURP),
                'telefono'         => trim($this->telefono),
                'perfil'           => trim($this->perfil),
                'color'            => trim($this->color),
                'foto'             => $this->foto,  // nombre del archivo en storage
                'status'           => $statusStr,
            ]);

            $this->dispatch('swal', [
                'title' => 'Profesor actualizado correctamente!',
                'icon'  => 'success',
                'position' => 'top-end',
            ]);

            // Cierra y limpia
            $this->cerrarModal();
            $this->dispatch('refreshProfesores');
            $this->dispatch('cerrar-modal-editar');
        }
    }

    public function cerrarModal()
    {
        $this->reset([
            'open', 'profesorId', 'user_id',
            'nombre', 'apellido_paterno', 'apellido_materno',
            'CURP', 'telefono', 'perfil', 'color',
            'foto', 'foto_nueva', 'status'
        ]);
    }

    public function render()
    {
        $usuarios = User::role('Profesor')
            ->where('status', "true")
            ->orderBy('id', 'desc')
            ->get();

        return view('livewire.admin.profesor.editar-profesor', compact('usuarios'));
    }
}
