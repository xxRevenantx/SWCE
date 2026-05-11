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
        $this->licenciaturas = Licenciatura::orderBy('id', 'asc')->get();
        $this->generaciones = Generacion::where('status', 'true')->get();
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

        // Verifica que la generación no sea más antigua de lo permitido.
        $generacionSeleccionada = Generacion::find($this->generacion_id);
        $limiteAnios = (int) env('SWCE_LIMITE_ANIOS_GENERACION_ANTERIOR', 10);

        if (!$this->generacionDentroDelLimite($generacionSeleccionada?->generacion, $limiteAnios)) {
            $this->dispatch('swal', [
                'title' => "La generación no puede ser anterior a {$limiteAnios} años.",
                'icon' => 'error',
                'position' => 'top',
            ]);
            return;
        }

        // Verifica si ya existe una asignación para la combinación seleccionada.
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


    private function generacionDentroDelLimite(?string $generacion, int $limiteAnios): bool
    {
        if (!$generacion) {
            return false;
        }

        preg_match('/(19|20)\d{2}/', $generacion, $coincidencias);

        if (empty($coincidencias[0])) {
            return true;
        }

        $anioInicio = (int) $coincidencias[0];
        $anioMinimo = now()->year - max(0, $limiteAnios);

        return $anioInicio >= $anioMinimo;
    }

    public function render()
    {
        return view('livewire.admin.asignar-generacion.crear-asignacion-generacion');
    }
}
