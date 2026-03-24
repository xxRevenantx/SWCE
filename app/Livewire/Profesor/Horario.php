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
        // Se busca el profesor relacionado al usuario autenticado
        $this->profesor = Profesor::where('user_id', Auth::id())->first();
        $this->profesor_id = $this->profesor?->id;

        // Se cargan los días
        $this->dias = Dia::orderBy('id')->get();

        // Se inicializan colecciones vacías
        $this->licenciaturas = collect();
        $this->cuatrimestres = collect();
        $this->generaciones = collect();

        // Se cargan filtros y horario inicial
        $this->cargarFiltros();
        $this->cargarHorasDisponibles();
        $this->inicializarMatriz();
        $this->cargarHorario();
    }

    public function updatedLicenciaturaId($value): void
    {
        $this->licenciatura_id = !empty($value) ? (int) $value : null;
        $this->cuatrimestre_id = null;
        $this->generacion_id = null;

        $this->cargarFiltros();
        $this->cargarHorasDisponibles();
        $this->cargarHorario();
    }

    public function updatedCuatrimestreId($value): void
    {
        $this->cuatrimestre_id = !empty($value) ? (int) $value : null;
        $this->generacion_id = null;

        $this->cargarFiltros();
        $this->cargarHorasDisponibles();
        $this->cargarHorario();
    }

    public function updatedGeneracionId($value): void
    {
        $this->generacion_id = !empty($value) ? (int) $value : null;

        $this->cargarHorasDisponibles();
        $this->cargarHorario();
    }

    public function limpiarFiltros(): void
    {
        $this->licenciatura_id = null;
        $this->cuatrimestre_id = null;
        $this->generacion_id = null;

        $this->cargarFiltros();
        $this->cargarHorasDisponibles();
        $this->cargarHorario();
    }

    public function baseConsulta()
    {
        // Se arma la consulta base del horario del profesor
        return DB::table('horarios')
            ->join('asignacion_materias', 'horarios.asignacion_materia_id', '=', 'asignacion_materias.id')
            ->leftJoin('materias', 'asignacion_materias.materia_id', '=', 'materias.id')
            ->leftJoin('licenciaturas', 'horarios.licenciatura_id', '=', 'licenciaturas.id')
            ->leftJoin('cuatrimestres', 'horarios.cuatrimestre_id', '=', 'cuatrimestres.id')
            ->leftJoin('generaciones', 'horarios.generacion_id', '=', 'generaciones.id')
            ->leftJoin('dias', 'horarios.dia_id', '=', 'dias.id')
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
            ->orderBy('id')
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
            $this->inicializarMatriz();
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

        $this->inicializarMatriz();
    }

    public function inicializarMatriz(): void
    {
        $this->matrizHorario = [];

        foreach ($this->horasDisponibles as $hora) {
            foreach ($this->dias as $dia) {
                // Cada celda guardará varias materias
                $this->matrizHorario[$hora][$dia->id] = [];
            }
        }
    }

    public function obtenerColorLicenciatura(?int $licenciaturaId): string
    {
        return match ($licenciaturaId) {
            1 => '#16a34a',
            2 => '#2563eb',
            3 => '#0f766e',
            4 => '#b91c1c',
            5 => '#7c3aed',
            6 => '#ea580c',
            default => '#334155',
        };
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
                'generaciones.generacion as generacion'
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

            if (!isset($this->matrizHorario[$hora])) {
                continue;
            }

            if (!isset($this->matrizHorario[$hora][$diaId])) {
                continue;
            }

            $colorLicenciatura = $this->obtenerColorLicenciatura($horario->licenciatura_id);

            // Se agregan todas las materias en la misma celda
            $this->matrizHorario[$hora][$diaId][] = [
                'materia' => $horario->materia ?? 'Sin materia',
                'licenciatura' => $horario->licenciatura ?? 'Sin licenciatura',
                'cuatrimestre' => $horario->cuatrimestre ?? 'Sin cuatrimestre',
                'generacion' => $horario->generacion ?? 'Sin generación',
                'hora' => $horario->hora ?? '',
                'color' => $colorLicenciatura,
            ];
        }
    }

    public function getFiltrosListosProperty(): bool
    {
        return !empty($this->licenciatura_id)
            && !empty($this->cuatrimestre_id)
            && !empty($this->generacion_id);
    }

    public function getPdfUrlProperty(): string
    {
        if (!$this->profesor_id) {
            return '';
        }

        $parametros = [
            'profesor' => $this->profesor_id,
        ];

        if (!empty($this->licenciatura_id)) {
            $parametros['licenciatura'] = $this->licenciatura_id;
        }

        if (!empty($this->generacion_id)) {
            $parametros['generacion'] = $this->generacion_id;
        }

        if (!empty($this->cuatrimestre_id)) {
            $parametros['cuatrimestre'] = $this->cuatrimestre_id;
        }

        return route('profesor.pdf.horario', $parametros);
    }

    public function getClasePdfProperty(): string
    {
        return 'inline-flex items-center gap-2 rounded-xl bg-red-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300';
    }

    public function render()
    {
        return view('livewire.profesor.horario');
    }
}
