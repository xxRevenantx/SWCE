<?php

namespace App\Livewire\Admin\Generacion;

use App\Models\Cuatrimestre;
use App\Models\Generacion;
use Livewire\Attributes\On;
use Livewire\Component;

class EditarGeneracion extends Component
{

    public $generacionId;
    public $generacion;
    public $status;
    public $open = false;



    #[On('editarModal')]
    public function editarModal($id)
    {
         $generacion = Generacion::findOrFail($id);
        $this->generacionId = $generacion->id;
        $this->generacion = $generacion->generacion;
        $this->status = $generacion->status == "true" ? true : false;
        $this->open = true;

           $this->dispatch('editar-cargado');
    }

    public function actualizarGeneracion()
    {
        $this->validate([
            'generacion' => 'required|string|max:9|unique:generaciones,generacion,' . $this->generacionId,
            'status' => 'required',
        ],[
            'generacion.required' => 'El campo generación es obligatorio.',
            'generacion.string' => 'El campo generación debe ser una cadena de texto.',
            'generacion.max' => 'El campo generación no puede tener más de 9 caracteres.',
            'generacion.unique' => 'La generacion ya existe en la base de datos.',
            'status.required' => 'El campo estado es obligatorio.',

        ]);

        if($this->status == true){
            $this->status = "true";
        }else{
            $this->status = "false";
        }

        $generacion = Generacion::find($this->generacionId);
        if ($generacion) {
            $generacion->update([
                'generacion' => trim($this->generacion),
                'status' => $this->status,
            ]);

            $this->dispatch('swal', [
                'title' => 'Generación actualizada correctamente!',
                'icon' => 'success',
                'position' => 'top-end',
            ]);

            $this->reset(['open', 'generacionId', 'generacion', 'status']);
            $this->dispatch('refreshGeneracion');

             // 👉 Avisamos al front que debe cerrar el modal
         $this->dispatch('cerrar-modal-editar');

        }
    }
    public function cerrarModal()
    {
        $this->reset(['open', 'generacionId', 'generacion', 'status']);
    }




    public function render()
    {

        return view('livewire.admin.generacion.editar-generacion');
    }
}
