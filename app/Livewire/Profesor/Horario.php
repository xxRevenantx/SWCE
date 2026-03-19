<?php

namespace App\Livewire\Profesor;

use App\Models\Cuatrimestre;
use App\Models\Dia;
use App\Models\Generacion;
use App\Models\Licenciatura;
use App\Models\Profesor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Horario extends Component
{
    public Collection $dias;
    public Collection $licenciaturas;
    public Collection $cuatrimestres;
    public Collection $generaciones;

    public ?int $licenciatura_id = null;
    public ?int $cuatrimestre_id = null;
    public ?int $generacion_id = null;

    public array $horasDisponibles = [];
    public array $matrizHorario = [];

    public ?Profesor $profesor = null;
    public ?int $profesor_id = null;

    public function mount(): void
    {
        $this->profesor = Profesor::where('user_id', Auth::id())->first();
        $this->profesor_id = $this->profesor?->id;

        $this->dias = Dia::orderBy('id')->get();
        $this->licenciaturas = collect();
        $this->cuatrimestres = collect();
        $this->generaciones = collect();

        $this->cargarFiltros();
        $this->cargarHorasDisponibles();
        $this->inicializarMatriz();
        $this->cargarHorario();
    }

    public function updatedLicenciaturaId(): void
    {
        $this->cuatrimestre_id = null;
        $this->generacion_id = null;

        $this->cargarFiltros();
        $this->cargarHorasDisponibles();
        $this->cargarHorario();
    }

    public function updatedCuatrimestreId(): void
    {
        $this->generacion_id = null;

        $this->cargarFiltros();
        $this->cargarHorasDisponibles();
        $this->cargarHorario();
    }

    public function updatedGeneracionId(): void
    {
        $this->cargarHorasDisponibles();
        $this->cargarHorario();
    }

    public function baseConsulta()
    {
        return DB::table('horarios')
            ->join('asignacion_materias', 'horarios.asignacion_materia_id', '=', 'asignacion_materias.id')
            ->leftJoin('materias', 'asignacion_materias.materia_id', '=', 'materias.id')
            ->leftJoin('licenciaturas', 'horarios.licenciatura_id', '=', 'licenciaturas.id')
            ->leftJoin('cuatrimestres', 'horarios.cuatrimestre_id', '=', 'cuatrimestres.id')
            ->leftJoin('generaciones', 'horarios.generacion_id', '=', 'generaciones.id')
            ->leftJoin('dias', 'horarios.dia_id', '=', 'dias.id')
            ->leftJoin('profesores', 'asignacion_materias.profesor_id', '=', 'profesores.id')
            ->where('asignacion_materias.profesor_id', $this->profesor_id);
    }

    public function cargarFiltros(): void
    {
        if (!$this->profesor_id) {
            $this->licenciaturas = collect();
            $this->cuatrimestres = collect();
            $this->generaciones = collect();
            return;
        }

        $consultaLicenciaturas = $this->baseConsulta();

        $idsLicenciaturas = $consultaLicenciaturas
            ->distinct()
            ->pluck('horarios.licenciatura_id')
            ->filter()
            ->values();

        $this->licenciaturas = Licenciatura::whereIn('id', $idsLicenciaturas)
            ->orderBy('nombre')
            ->get();

        $consultaCuatrimestres = $this->baseConsulta();

        if ($this->licenciatura_id) {
            $consultaCuatrimestres->where('horarios.licenciatura_id', $this->licenciatura_id);
        }

        $idsCuatrimestres = $consultaCuatrimestres
            ->distinct()
            ->pluck('horarios.cuatrimestre_id')
            ->filter()
            ->values();

        $this->cuatrimestres = Cuatrimestre::whereIn('id', $idsCuatrimestres)
            ->orderBy('no_cuatrimestre')
            ->get();

        $consultaGeneraciones = $this->baseConsulta();

        if ($this->licenciatura_id) {
            $consultaGeneraciones->where('horarios.licenciatura_id', $this->licenciatura_id);
        }

        if ($this->cuatrimestre_id) {
            $consultaGeneraciones->where('horarios.cuatrimestre_id', $this->cuatrimestre_id);
        }

        $idsGeneraciones = $consultaGeneraciones
            ->distinct()
            ->pluck('horarios.generacion_id')
            ->filter()
            ->values();

        $this->generaciones = Generacion::whereIn('id', $idsGeneraciones)
            ->orderBy('generacion')
            ->get();
    }

    public function cargarHorasDisponibles(): void
    {
        if (!$this->profesor_id) {
            $this->horasDisponibles = [];
            return;
        }

        $consulta = $this->baseConsulta();

        if ($this->licenciatura_id) {
            $consulta->where('horarios.licenciatura_id', $this->licenciatura_id);
        }

        if ($this->cuatrimestre_id) {
            $consulta->where('horarios.cuatrimestre_id', $this->cuatrimestre_id);
        }

        if ($this->generacion_id) {
            $consulta->where('horarios.generacion_id', $this->generacion_id);
        }

        $this->horasDisponibles = $consulta
            ->select('horarios.hora')
            ->distinct()
            ->orderByRaw("
            STR_TO_DATE(
                TRIM(SUBSTRING_INDEX(horarios.hora, '-', 1)),
                '%h:%i%p'
            ) asc
        ")
            ->pluck('horarios.hora')
            ->filter()
            ->values()
            ->toArray();
    }

    public function inicializarMatriz(): void
    {
        $this->matrizHorario = [];

        foreach ($this->horasDisponibles as $hora) {
            foreach ($this->dias as $dia) {
                $this->matrizHorario[$hora][$dia->id] = null;
            }
        }
    }

    public function cargarHorario(): void
    {
        $this->inicializarMatriz();

        if (!$this->profesor_id) {
            return;
        }

        $consulta = $this->baseConsulta()
            ->select(
                'horarios.id',
                'horarios.hora',
                'horarios.dia_id',
                'horarios.licenciatura_id',
                'horarios.cuatrimestre_id',
                'horarios.generacion_id',
                'materias.nombre as materia',
                'licenciaturas.nombre as licenciatura',
                'cuatrimestres.nombre_cuatrimestre as cuatrimestre',
                'generaciones.generacion as generacion',
                'profesores.color as color_profesor'
            );

        if ($this->licenciatura_id) {
            $consulta->where('horarios.licenciatura_id', $this->licenciatura_id);
        }

        if ($this->cuatrimestre_id) {
            $consulta->where('horarios.cuatrimestre_id', $this->cuatrimestre_id);
        }

        if ($this->generacion_id) {
            $consulta->where('horarios.generacion_id', $this->generacion_id);
        }

        $horarios = $consulta
            ->orderByRaw("
            STR_TO_DATE(
                TRIM(SUBSTRING_INDEX(horarios.hora, '-', 1)),
                '%h:%i%p'
            ) asc
        ")
            ->orderBy('horarios.dia_id')
            ->get();

        foreach ($horarios as $horario) {
            $hora = $horario->hora;
            $diaId = $horario->dia_id;

            if (!array_key_exists($hora, $this->matrizHorario)) {
                continue;
            }

            if (!array_key_exists($diaId, $this->matrizHorario[$hora])) {
                continue;
            }

            $this->matrizHorario[$hora][$diaId] = [
                'materia' => $horario->materia ?? 'Sin materia',
                'licenciatura' => $horario->licenciatura ?? 'Sin licenciatura',
                'cuatrimestre' => $horario->cuatrimestre ?? 'Sin cuatrimestre',
                'generacion' => $horario->generacion ?? 'Sin generación',
                'hora' => $horario->hora ?? '',
                'color' => $horario->color_profesor ?: '#2563eb',
            ];
        }
    }

    public function obtenerColorTexto(string $colorHex): string
    {
        $colorHex = ltrim($colorHex, '#');

        if (strlen($colorHex) !== 6) {
            return '#ffffff';
        }

        $rojo = hexdec(substr($colorHex, 0, 2));
        $verde = hexdec(substr($colorHex, 2, 2));
        $azul = hexdec(substr($colorHex, 4, 2));

        $luminosidad = (($rojo * 299) + ($verde * 587) + ($azul * 114)) / 1000;

        return $luminosidad > 155 ? '#000000' : '#ffffff';
    }

    public function render()
    {
        return view('livewire.profesor.horario');
    }
}
