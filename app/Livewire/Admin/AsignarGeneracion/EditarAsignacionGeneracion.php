<?php

namespace App\Livewire\Admin\AsignarGeneracion;

use App\Models\AsignarGeneracion;
use App\Models\Generacion;
use App\Models\Licenciatura;
use Livewire\Attributes\On;
use Livewire\Component;

class EditarAsignacionGeneracion extends Component
{
    public $asignacionId;
    public $licenciatura_id;
    public $generacion_id;
    public $open = false;


    public $nombreGeneracion;



    #[On('editarModal')]
    public function editarModal($id)
    {
        $asignacion = AsignarGeneracion::findOrFail($id);
        $this->asignacionId = $asignacion->id;
        $this->licenciatura_id = $asignacion->licenciatura_id;
        $this->generacion_id = $asignacion->generacion_id;
        $this->open = true;


        $this->nombreGeneracion = Generacion::find($this->generacion_id)->generacion;



        $this->dispatch('editar-cargado');
    }

    public function actualizarAsignacionGeneracion()
    {
        $this->validate([
            'licenciatura_id' => 'required|integer|exists:licenciaturas,id',
            'generacion_id' => 'required|integer|exists:generaciones,id',
        ], [
            'licenciatura_id.required' => 'La licenciatura es obligatoria.',
            'generacion_id.required' => 'La generación es obligatoria.',

        ]);

        // Verifica si ya existe una asignación para la combinación seleccionada
        $existingAssignment = AsignarGeneracion::where('licenciatura_id', $this->licenciatura_id)
            ->where('generacion_id', $this->generacion_id)
            ->where('id', '!=', $this->asignacionId)
            ->first();
        if ($existingAssignment) {
            $this->dispatch('swal', [
                'title' => 'Ya existe una asignación para esta combinación.',
                'icon' => 'error',
                'position' => 'top',
            ]);
            return;
        }



        $asignacion = AsignarGeneracion::find($this->asignacionId);
        if ($asignacion) {
            $asignacion->update([
                'licenciatura_id' => $this->licenciatura_id,
                'generacion_id' => $this->generacion_id,
            ]);

            $this->dispatch('swal', [
                'title' => 'Asignación actualizada correctamente!',
                'icon' => 'success',
                'position' => 'top-end',
            ]);

            $this->reset(['open', 'asignacionId', 'licenciatura_id', 'generacion_id']);
            $this->dispatch('refreshAsignacion');

            // 👉 Avisamos al front que debe cerrar el modal
            $this->dispatch('cerrar-modal-editar');
        }
    }
    public function cerrarModal()
    {
        $this->reset(['open', 'asignacionId', 'licenciatura_id', 'generacion_id']);
    }

    public function render()
    {
        $licenciaturas = Licenciatura::orderBy('id', 'desc')->get();
        $generaciones = Generacion::where('status', 'true')->orderBy('generacion')->get();
        return view('livewire.admin.asignar-generacion.editar-asignacion-generacion', compact('licenciaturas', 'generaciones'));
    }
}
