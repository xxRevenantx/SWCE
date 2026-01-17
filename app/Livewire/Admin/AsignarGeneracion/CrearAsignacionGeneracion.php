<?php

namespace App\Livewire\Admin\AsignarGeneracion;

use App\Models\AsignarGeneracion;
use App\Models\Generacion;
use App\Models\Licenciatura;
use Livewire\Component;

class CrearAsignacionGeneracion extends Component
{

    public $licenciaturas;
    public $generaciones;

    public $licenciatura_id;
    public $generacion_id;


    public function mount()
    {
        $this->licenciaturas = Licenciatura::all();
        $this->generaciones = Generacion::all();
    }


    public function asignarGeneracion()
    {

        $this->validate([
            'licenciatura_id' => 'required|exists:licenciaturas,id',
            'generacion_id' => 'required|exists:generaciones,id',
        ], [
            'licenciatura_id.required' => 'La licenciatura es obligatoria.',
            'licenciatura_id.exists' => 'La licenciatura seleccionada no es válida.',
            'generacion_id.required' => 'La generación es obligatoria.',
            'generacion_id.exists' => 'La generación seleccionada no es válida.',
        ]);

        // Verifica si ya existe una asignación para la combinación seleccionada
        $existingAssignment = AsignarGeneracion::where('licenciatura_id', $this->licenciatura_id)
            ->where('generacion_id', $this->generacion_id)
            ->first();
        if ($existingAssignment) {
            $this->dispatch('swal', [
                'title' => 'Ya existe una asignación para esta combinación.',
                'icon' => 'error',
                'position' => 'top',
            ]);
            return;
        }


        // Aquí se agrega la lógica para asignar la generación a la licenciatura  seleccionadas
        AsignarGeneracion::create([
            'licenciatura_id' => $this->licenciatura_id,
            'generacion_id' => $this->generacion_id,
        ]);

        $this->dispatch('swal', [
            'title' => 'Asignación creada correctamente',
            'icon' => 'success',
            'position' => 'top-end',
        ]);

        $this->reset('licenciatura_id', 'generacion_id');

        $this->dispatch('refreshAsignacion');
    }


    public function render()
    {
        return view('livewire.admin.asignar-generacion.crear-asignacion-generacion');
    }
}
