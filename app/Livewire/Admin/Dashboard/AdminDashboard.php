<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Generacion;
use App\Models\Inscripcion;
use App\Models\Licenciatura;
use App\Models\Profesor;
use Livewire\Component;

class AdminDashboard extends Component
{
    public $licenciaturas;
    public $generacionesActivas;
    public $resumenPorLicenciatura = [];
    public $profesoresActivos;

    public $totalActivos = 0;
    public $totalHombresActivos = 0;
    public $totalMujeresActivos = 0;

    public $resumenPorLicenciaturaBaja = [];
    public $totalBaja = 0;
    public $totalHombresBaja = 0;
    public $totalMujeresBaja = 0;

    public array $categoriasGrafica = [];
    public array $seriesGrafica = [];

    public function mount(): void
    {
        $this->cargarDatosDashboard();
    }

    // Aquí cargo toda la información del tablero.
    public function cargarDatosDashboard(): void
    {
        $this->licenciaturas = Licenciatura::orderBy('id', 'desc')->get();

        // Aquí obtengo las generaciones activas.
        $this->generacionesActivas = Generacion::where('status', 'true')->get();

        // Aquí obtengo los profesores activos según el usuario relacionado.
        $this->profesoresActivos = Profesor::whereHas('user', function ($query) {
            $query->where('status', 'true');
        })->get();

        // Esta base solo toma inscripciones cuya generación esté activa.
        $base = Inscripcion::query()
            ->whereHas('generacion', function ($query) {
                $query->where('status', 'true');
            });

        // Aquí construyo el resumen de alumnos activos por licenciatura.
        $this->resumenPorLicenciatura = $this->licenciaturas->map(function ($licenciatura) use ($base) {
            $hombres = (clone $base)
                ->where('licenciatura_id', $licenciatura->id)
                ->where('status', 1)
                ->whereHas('alumno', fn($query) => $query->where('sexo', 'M'))
                ->count();

            $mujeres = (clone $base)
                ->where('licenciatura_id', $licenciatura->id)
                ->where('status', 1)
                ->whereHas('alumno', fn($query) => $query->where('sexo', 'F'))
                ->count();

            return [
                'licenciatura' => $licenciatura->nombre,
                'hombres' => $hombres,
                'mujeres' => $mujeres,
                'total' => $hombres + $mujeres,
            ];
        })->values()->toArray();

        // Aquí obtengo los totales de alumnos activos.
        $this->totalActivos = (clone $base)
            ->where('status', 1)
            ->count();

        $this->totalHombresActivos = (clone $base)
            ->where('status', 1)
            ->whereHas('alumno', fn($query) => $query->where('sexo', 'M'))
            ->count();

        $this->totalMujeresActivos = (clone $base)
            ->where('status', 1)
            ->whereHas('alumno', fn($query) => $query->where('sexo', 'F'))
            ->count();

        // Aquí construyo el resumen de alumnos inactivos por licenciatura.
        $this->resumenPorLicenciaturaBaja = $this->licenciaturas->map(function ($licenciatura) use ($base) {
            $hombres = (clone $base)
                ->where('licenciatura_id', $licenciatura->id)
                ->where('status', 0)
                ->whereHas('alumno', fn($query) => $query->where('sexo', 'M'))
                ->count();

            $mujeres = (clone $base)
                ->where('licenciatura_id', $licenciatura->id)
                ->where('status', 0)
                ->whereHas('alumno', fn($query) => $query->where('sexo', 'F'))
                ->count();

            return [
                'licenciatura' => $licenciatura->nombre,
                'hombres' => $hombres,
                'mujeres' => $mujeres,
                'total' => $hombres + $mujeres,
            ];
        })->values()->toArray();

        // Aquí obtengo los totales de alumnos inactivos.
        $this->totalBaja = (clone $base)
            ->where('status', 0)
            ->count();

        $this->totalHombresBaja = (clone $base)
            ->where('status', 0)
            ->whereHas('alumno', fn($query) => $query->where('sexo', 'M'))
            ->count();

        $this->totalMujeresBaja = (clone $base)
            ->where('status', 0)
            ->whereHas('alumno', fn($query) => $query->where('sexo', 'F'))
            ->count();

        // Aquí preparo los datos que usará ApexCharts.
        $this->categoriasGrafica = collect($this->resumenPorLicenciatura)
            ->pluck('licenciatura')
            ->toArray();

        $this->seriesGrafica = [
            [
                'name' => 'Activos Hombres',
                'data' => collect($this->resumenPorLicenciatura)->pluck('hombres')->map(fn($valor) => (int) $valor)->toArray(),
            ],
            [
                'name' => 'Activos Mujeres',
                'data' => collect($this->resumenPorLicenciatura)->pluck('mujeres')->map(fn($valor) => (int) $valor)->toArray(),
            ],
            [
                'name' => 'Bajas Hombres',
                'data' => collect($this->resumenPorLicenciaturaBaja)->pluck('hombres')->map(fn($valor) => (int) $valor)->toArray(),
            ],
            [
                'name' => 'Bajas Mujeres',
                'data' => collect($this->resumenPorLicenciaturaBaja)->pluck('mujeres')->map(fn($valor) => (int) $valor)->toArray(),
            ],
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard.admin-dasboard');
    }
}
