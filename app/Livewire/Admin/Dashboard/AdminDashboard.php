<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Generacion;
use Livewire\Component;

class AdminDashboard extends Component
{


    public $licenciaturas;
    public $generacionesActivas;


    public function mount()
    {

        $this->licenciaturas = \App\Models\Licenciatura::all() ?? '';

        $this->generacionesActivas = Generacion::where('status', 'true')->get();

        // $this->profesoresActivos = Profesor::whereHas('user', function ($query) {
        //     $query->where('status', 'true');
        // })->get();

        // $this->resumenPorLicenciatura = $this->licenciaturas->map(function ($licenciatura) {

        //     $hombres = Inscripcion::where('licenciatura_id', $licenciatura->id)
        //         ->where('foraneo', "false")
        //         ->where('status', "true")
        //         ->where('sexo', 'H')
        //         ->get()
        //         ->filter(function ($inscripcion) {
        //             return $inscripcion->generacion && $inscripcion->generacion->activa == "true";
        //         })
        //         ->count();

        //     $mujeres = Inscripcion::where('licenciatura_id', $licenciatura->id)
        //         ->where('foraneo', "false")
        //         ->where('status', "true")
        //         ->where('sexo', 'M')
        //         ->get()
        //         ->filter(function ($inscripcion) {
        //             return $inscripcion->generacion && $inscripcion->generacion->activa == "true";
        //         })
        //         ->count();

        //     return [
        //         'licenciatura' => $licenciatura->nombre,
        //         'hombres' => $hombres,
        //         'mujeres' => $mujeres,
        //         'total' => $hombres + $mujeres
        //     ];
        // });

        // $this->totalLocalesActivos = Inscripcion::where('foraneo', "false")
        //     ->where('status', "true")
        //     ->get()
        //     ->filter(function ($inscripcion) {
        //     return $inscripcion->generacion && $inscripcion->generacion->activa == "true";
        //     })
        //     ->count();

        // $this->totalHombresLocalesActivos = Inscripcion::where('foraneo', "false")
        //     ->where('status', "true")
        //     ->where('sexo', 'H')
        //     ->get()
        //     ->filter(function ($inscripcion) {
        //     return $inscripcion->generacion && $inscripcion->generacion->activa == "true";
        //     })
        //     ->count();

        // $this->totalMujeresLocalesActivos = Inscripcion::where('foraneo', "false")
        //     ->where('status', "true")
        //     ->where('sexo', 'M')
        //     ->get()
        //     ->filter(function ($inscripcion) {
        //     return $inscripcion->generacion && $inscripcion->generacion->activa == "true";
        //     })
        //     ->count();


        // $this->resumenPorLicenciaturaBaja = $this->licenciaturas->map(function ($licenciatura) {

        //     $hombres = Inscripcion::where('licenciatura_id', $licenciatura->id)
        //         ->where('foraneo', "false")
        //         ->where('status', "false")
        //         ->where('sexo', 'H')
        //         ->get()
        //         ->filter(function ($inscripcion) {
        //             return $inscripcion->generacion && $inscripcion->generacion->activa == "true";
        //         })
        //         ->count();

        //     $mujeres = Inscripcion::where('licenciatura_id', $licenciatura->id)
        //         ->where('foraneo', "false")
        //         ->where('status', "false")
        //         ->where('sexo', 'M')
        //         ->get()
        //         ->filter(function ($inscripcion) {
        //             return $inscripcion->generacion && $inscripcion->generacion->activa == "true";
        //         })
        //         ->count();

        //         return [
        //             'licenciatura' => $licenciatura->nombre,
        //             'hombres' => $hombres,
        //             'mujeres' => $mujeres,
        //             'total' => $hombres + $mujeres
        //         ];
        //     });


        // $this->totalLocalesBaja = Inscripcion::where('foraneo', "false")
        //     ->where('status', "false")
        //     ->get()
        //     ->filter(function ($inscripcion) {
        //             return $inscripcion->generacion && $inscripcion->generacion->activa == "true";
        //         })
        //     ->count();

        // $this->totalHombresLocalesBaja = Inscripcion::where('foraneo', "false")
        //     ->where('status', "false")
        //     ->where('sexo', 'H')
        //     ->get()
        //     ->filter(function ($inscripcion) {
        //             return $inscripcion->generacion && $inscripcion->generacion->activa == "true";
        //         })
        //     ->count();

        // $this->totalMujeresLocalesBaja = Inscripcion::where('foraneo', "false")
        // ->where('status', "false")
        // ->where('sexo', 'M')
        // ->get()
        // ->filter(function ($inscripcion) {
        //         return $inscripcion->generacion && $inscripcion->generacion->activa == "true";
        //     })
        // ->count();




    }
    public function render()
    {
        return view('livewire.admin.dashboard.admin-dasboard');
    }
}
