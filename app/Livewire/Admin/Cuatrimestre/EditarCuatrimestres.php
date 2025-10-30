<?php

namespace App\Livewire\Admin\Cuatrimestre;

use App\Models\Cuatrimestre;
use Livewire\Attributes\On;
use Livewire\Component;

class EditarCuatrimestres extends Component
{
    public $cuatrimestreId;
    public $no_cuatrimestre;
    public $nombre_cuatrimestre;
    public $mes_id;
    public $open = false;



    #[On('editarCuatrimestre')]
            public function editarCuatrimestre($id)
            {
                $cuatrimestre = Cuatrimestre::select('id','no_cuatrimestre','nombre_cuatrimestre','mes_id')->findOrFail($id);

                $this->cuatrimestreId     = $cuatrimestre->id;
                $this->no_cuatrimestre    = $cuatrimestre->no_cuatrimestre;
                $this->nombre_cuatrimestre= $cuatrimestre->nombre_cuatrimestre;
                $this->mes_id             = $cuatrimestre->mes_id;

                // avisa al front que ya hay datos
                $this->dispatch('cuatrimestre-cargado');
            }

    public function actualizarCuatrimestre()
    {
        $this->validate([
            'no_cuatrimestre' => 'required|numeric|min:1|max:9|unique:cuatrimestres,no_cuatrimestre,' . $this->cuatrimestreId,
            'nombre_cuatrimestre' => 'required|string|max:15|unique:cuatrimestres,nombre_cuatrimestre,' . $this->cuatrimestreId,
            'mes_id' => 'required|exists:meses,id',
        ], [

            'no_cuatrimestre.unique' => 'El cuatrimestre ya existe.',
            'cuatrimestre.required' => 'El campo cuatrimestre es obligatorio.',
            'cuatrimestre.numeric' => 'El campo cuatrimestre debe ser un número.',
            'cuatrimestre.min' => 'El cuatrimestre debe ser al menos 1.',
            'cuatrimestre.max' => 'El cuatrimestre no puede ser mayor a 10.',
            'nombre_cuatrimestre.required' => 'El campo nombre del cuatrimestre es obligatorio.',
            'nombre_cuatrimestre.string' => 'El campo nombre del cuatrimestre debe ser una cadena de texto.',
            'nombre_cuatrimestre.max' => 'El campo nombre del cuatrimestre no puede tener más de 15 caracteres.',
            'nombre_cuatrimestre.unique' => 'El nombre del cuatrimestre ya existe.',
            'mes_id.required' => 'El campo meses es obligatorio.',
            'mes_id.exists' => 'El mes seleccionado no es válido.',

            ]);



        $asignacion = Cuatrimestre::findOrFail($this->cuatrimestreId);
        $asignacion->update([
            'no_cuatrimestre' => $this->no_cuatrimestre,
            'nombre_cuatrimestre' => strtoupper(trim($this->nombre_cuatrimestre)),
            'mes_id' => $this->mes_id,
        ]);


        $this->dispatch('refreshCuatrimestre');

        $this->dispatch('swal', [
            'title' => '¡Cuatrimestre actualizado correctamente!',
            'icon' => 'success',
            'position' => 'top-end',
        ]);

        $this->cerrarModal();

            // 👉 Avisamos al front que debe cerrar el modal
        $this->dispatch('cerrar-modal-cuatrimestre');
    }



    public function cerrarModal()
    {
        $this->reset(['open', 'cuatrimestreId', 'no_cuatrimestre', 'nombre_cuatrimestre', 'mes_id']);
          $this->resetValidation();
    }


    public function render()
    {
        $meses = \App\Models\Mes::all();
        return view('livewire.admin.cuatrimestre.editar-cuatrimestres', compact('meses'));
    }
}
