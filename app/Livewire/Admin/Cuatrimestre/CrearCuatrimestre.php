<?php

namespace App\Livewire\Admin\Cuatrimestre;

use App\Models\Cuatrimestre;
use App\Models\Mes;
use Livewire\Component;

class CrearCuatrimestre extends Component
{


     public $no_cuatrimestre;
    public $nombre_cuatrimestre;
    public $mes_id;



    protected $rules = [
        'no_cuatrimestre' => 'required|numeric|min:1|max:9',
        'nombre_cuatrimestre' => 'required|string|max:15',
        'mes_id' => 'required|exists:meses,id',
    ];

    protected $messages = [

        'no_cuatrimestre.required' => 'El campo cuatrimestre es obligatorio.',
        'no_cuatrimestre.numeric' => 'El campo cuatrimestre debe ser un número.',
        'no_cuatrimestre.min' => 'El campo cuatrimestre debe ser al menos 1.',
        'no_cuatrimestre.max' => 'El campo cuatrimestre no puede ser mayor a 9.',
        'nombre_cuatrimestre.required' => 'El campo nombre del cuatrimestre es obligatorio.',
        'nombre_cuatrimestre.string' => 'El campo nombre del cuatrimestre debe ser una cadena de texto.',
        'nombre_cuatrimestre.max' => 'El campo nombre del cuatrimestre no puede tener más de 15 caracteres.',
        'mes_id.required' => 'El campo mes es obligatorio.',
        'mes_id.exists' => 'El mes seleccionado no es válido.',

    ];

    public function crearCuatrimestre()
    {
        $this->validate();


        $cuatrimestreExistente = Cuatrimestre::where('no_cuatrimestre', $this->no_cuatrimestre)
            ->first();
        if ($cuatrimestreExistente) {
            $this->dispatch('swal', [
                'title' => '¡El cuatrimestre ya existe!',
                'icon' => 'error',
                'position' => 'top',
            ]);
            return;
        }

        // Aquí puedes agregar la lógica para crear el cuatrimestre

        Cuatrimestre::create([
            'no_cuatrimestre' => $this->no_cuatrimestre,
            'nombre_cuatrimestre' => $this->nombre_cuatrimestre,
            'mes_id' => $this->mes_id,
        ]);
        // Luego, puedes restablecer los campos
        $this->reset([
            'no_cuatrimestre',
            'nombre_cuatrimestre',
            'mes_id',
        ]);
        // O redirigir a otra página

        $this->dispatch('swal', [
            'title' => '¡Cuatrimestre creado correctamente!',
            'icon' => 'success',
            'position' => 'top-end',
        ]);


        $this->dispatch('refreshCuatrimestre');


    }

    public function render()
    {
        $meses = Mes::all();
        return view('livewire.admin.cuatrimestre.crear-cuatrimestre', compact('meses'));
    }
}
