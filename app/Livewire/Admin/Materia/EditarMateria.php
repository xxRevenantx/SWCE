<?php

namespace App\Livewire\Admin\Materia;

use App\Models\Cuatrimestre;
use App\Models\Licenciatura;
use App\Models\Materia;
use Livewire\Attributes\On;
use Livewire\Component;

class EditarMateria extends Component
{
    public $nombre;
    public $slug;
    public $clave;
    public $creditos;
    public $calificable;
    public $cuatrimestre_id;
    public $licenciatura_id;



    public $materiaId;
    public $open = false;


    #[On('editarModal')]
    public function editarModal($id)
    {
        $materia = Materia::findOrFail($id);
        $this->materiaId = $materia->id;
        $this->nombre = $materia->nombre;
        $this->slug = $materia->slug;
        $this->clave = $materia->clave;
        $this->creditos = $materia->creditos;
        $this->calificable = $materia->calificable;
        $this->cuatrimestre_id = $materia->cuatrimestre_id;
        $this->licenciatura_id = $materia->licenciatura_id;
        $this->open = true;

        $this->dispatch('editar-cargado');
    }

    public function actualizarMateria()
    {
        $this->validate([
            'nombre' => 'required|string|max:255|unique:materias,nombre,' . $this->materiaId,
            'slug' => 'required|string|max:255|unique:materias,slug,' . $this->materiaId,
            'clave' => 'required|string|max:50|unique:materias,clave,' . $this->materiaId,
            'creditos' => 'required|integer|min:0',
            'calificable' => 'required|in:si,no',
            'cuatrimestre_id' => 'required|exists:cuatrimestres,id',
            'licenciatura_id' => 'required|exists:licenciaturas,id',
        ], [
            'nombre.required' => 'El nombre de la materia es requerido',
            'slug.required' => 'El slug de la materia es requerido',
            'slug.unique' => 'El slug de la materia ya existe',
            'clave.required' => 'La clave de la materia es requerida',
            'clave.unique' => 'La clave de la materia ya existe',
            'creditos.required' => 'Los creditos son requeridos',
            'cuatrimestre_id.required' => 'El cuatrimestre es requerido',
            'licenciatura_id.required' => 'La licenciatura es requerida',


        ]);

        $materia = Materia::find($this->materiaId);
        if ($materia) {
            $materia->update([
                'nombre' => trim($this->nombre),
                'slug' => $this->slug,
                'clave' => $this->clave,
                'creditos' => $this->creditos,
                'calificable' => $this->calificable,
                'cuatrimestre_id' => $this->cuatrimestre_id,
                'licenciatura_id' => $this->licenciatura_id,
            ]);

            $this->dispatch('swal', [
                'title' => 'Materia actualizada correctamente!',
                'icon' => 'success',
                'position' => 'top-end',
            ]);

            $this->reset(['open', 'materiaId', 'nombre', 'slug', 'clave', 'creditos', 'calificable', 'cuatrimestre_id', 'licenciatura_id']);
            $this->dispatch('refreshMaterias');

            // 👉 Avisamos al front que debe cerrar el modal
            $this->dispatch('cerrar-modal-editar');
        }
    }
    public function cerrarModal()
    {
        $this->reset(['open', 'materiaId', 'nombre', 'slug', 'clave', 'creditos', 'calificable', 'cuatrimestre_id', 'licenciatura_id']);
    }


    public function render()
    {
        $licenciaturas = Licenciatura::orderBy('id', 'desc')->get();
        $cuatrimestres = Cuatrimestre::orderBy('id', 'desc')->get();
        return view('livewire.admin.materia.editar-materia', compact('licenciaturas', 'cuatrimestres'));
    }
}
