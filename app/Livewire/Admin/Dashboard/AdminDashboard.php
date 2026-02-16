<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Generacion;
use App\Models\Inscripcion;
use App\Models\Profesor;
use Livewire\Component;

class AdminDashboard extends Component
{
    public $licenciaturas;
    public $generacionesActivas;

    public $resumenPorLicenciatura = [];
    public $profesoresActivos;

    public $totalActivos;
    public $totalHombresActivos;
    public $totalMujeresActivos;

    public $resumenPorLicenciaturaBaja = [];
    public $totalBaja;
    public $totalHombresBaja;
    public $totalMujeresBaja;

    public function mount()
    {
        $this->licenciaturas = \App\Models\Licenciatura::orderBy('id', 'desc')->get();



        // Generaciones activas: en BD es generaciones.status (enum 'true'/'false')
        $this->generacionesActivas = Generacion::where('status', 'true')->get();

        // Profesores activos
        $this->profesoresActivos = Profesor::whereHas('user', function ($query) {
            $query->where('status', 'true');
        })->get();

        /**
         * SOLO inscripciones cuya generación esté activa (generaciones.status = 'true')
         */
        $base = Inscripcion::query()
            ->whereHas('generacion', function ($q) {
                $q->where('status', 'true');
            });


        $this->resumenPorLicenciatura = $this->licenciaturas->map(function ($licenciatura) use ($base) {

            $hombres = (clone $base)
                ->where('licenciatura_id', $licenciatura->id)
                ->where('status', 1)
                ->whereHas('alumno', fn($q) => $q->where('sexo', 'M'))
                ->count();

            $mujeres = (clone $base)
                ->where('licenciatura_id', $licenciatura->id)
                ->where('status', 1)
                ->whereHas('alumno', fn($q) => $q->where('sexo', 'F'))
                ->count();

            return [
                'licenciatura' => $licenciatura->nombre,
                'hombres' => $hombres,
                'mujeres' => $mujeres,
                'total' => $hombres + $mujeres,
            ];
        });

        // TOTALES ACTIVOS
        $this->totalActivos = (clone $base)
            ->where('status', 1)
            ->count();

        $this->totalHombresActivos = (clone $base)
            ->where('status', 1)
            ->whereHas('alumno', fn($q) => $q->where('sexo', 'M'))
            ->count();

        $this->totalMujeresActivos = (clone $base)
            ->where('status', 1)
            ->whereHas('alumno', fn($q) => $q->where('sexo', 'F'))
            ->count();

        /**
         * RESUMEN POR LICENCIATURA (BAJA)
         * inscripciones.status = 0
         */
        $this->resumenPorLicenciaturaBaja = $this->licenciaturas->map(function ($licenciatura) use ($base) {

            $hombres = (clone $base)
                ->where('licenciatura_id', $licenciatura->id)
                ->where('status', 0)
                ->whereHas('alumno', fn($q) => $q->where('sexo', 'M'))
                ->count();

            $mujeres = (clone $base)
                ->where('licenciatura_id', $licenciatura->id)
                ->where('status', 0)
                ->whereHas('alumno', fn($q) => $q->where('sexo', 'F'))
                ->count();

            return [
                'licenciatura' => $licenciatura->nombre,
                'hombres' => $hombres,
                'mujeres' => $mujeres,
                'total' => $hombres + $mujeres,
            ];
        });

        // TOTALES BAJA
        $this->totalBaja = (clone $base)
            ->where('status', 0)
            ->count();

        $this->totalHombresBaja = (clone $base)
            ->where('status', 0)
            ->whereHas('alumno', fn($q) => $q->where('sexo', 'M'))
            ->count();

        $this->totalMujeresBaja = (clone $base)
            ->where('status', 0)
            ->whereHas('alumno', fn($q) => $q->where('sexo', 'F'))
            ->count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.admin-dasboard');
    }
}
